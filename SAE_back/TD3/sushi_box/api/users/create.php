<?php

// Passage d’une commande après authentification
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {

    // --- Vérification méthode HTTP ---
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception("Méthode non autorisée");
    }

    // --- Lecture et validation JSON ---
    $input = json_decode(file_get_contents("php://input"), true);

    if (
        !$input ||
        !isset($input['user_id']) ||
        !isset($input['items']) ||
        !is_array($input['items']) ||
        count($input['items']) === 0
    ) {
        http_response_code(400);
        throw new Exception("JSON invalide, user_id ou items manquants");
    }

    // --- Connexion BDD ---
    $pdo = new PDO(
        "mysql:host=localhost;dbname=api;charset=utf8",
        "root",
        "root",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // --- Début transaction ---
    $pdo->beginTransaction();

    // --- Création commande ---
    $stmtOrder = $pdo->prepare(
        "INSERT INTO orders (user_id, created_at)
         VALUES (:user_id, NOW())"
    );

    $stmtOrder->execute([
        'user_id' => $input['user_id']
    ]);

    $orderId = $pdo->lastInsertId();

    // --- Préparation requêtes ---
    $stmtPrice = $pdo->prepare(
        "SELECT price FROM boxes WHERE id = :id"
    );

    $stmtItem = $pdo->prepare(
        "INSERT INTO order_items (order_id, box_id, quantity, unit_price)
         VALUES (:order_id, :box_id, :quantity, :unit_price)"
    );

    // --- Insertion des items ---
    foreach ($input['items'] as $item) {

        if (!isset($item['box_id'], $item['quantity'])) {
            throw new Exception("Chaque item doit contenir box_id et quantity");
        }

        $stmtPrice->execute(['id' => $item['box_id']]);
        $box = $stmtPrice->fetch();

        if (!$box) {
            throw new Exception("Box introuvable (ID {$item['box_id']})");
        }

        $stmtItem->execute([
            'order_id'   => $orderId,
            'box_id'     => $item['box_id'],
            'quantity'   => $item['quantity'],
            'unit_price' => $box['price']
        ]);
    }

    // --- Validation transaction ---
    $pdo->commit();

    http_response_code(201);
    echo json_encode([
        "success"  => true,
        "order_id" => (int)$orderId
    ]);

} catch (Throwable $e) {

    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error"   => $e->getMessage()
    ]);
}
