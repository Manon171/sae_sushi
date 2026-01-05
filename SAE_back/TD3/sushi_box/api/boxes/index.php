<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Connexion à la BDD
    $pdo = new PDO('mysql:host=localhost;dbname=api;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération d'une boîte spécifique ou de toutes les boîtes
    if (!empty($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM boxes WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $boxes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query("SELECT * FROM boxes");
        $boxes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Si aucune boîte : retourner un tableau vide
    if (!$boxes) {
        ob_end_clean(); // vide le buffer avant réponse
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([]);
        exit;
    }

    // Préparation de la requête pour les saveurs
    $stmtFlavors = $pdo->prepare("
        SELECT fl.name 
        FROM box_flavors bf
        INNER JOIN flavors fl ON bf.flavor_id = fl.id
        WHERE bf.box_id = :id
    ");

    // Ajout des saveurs à chaque box
    foreach ($boxes as &$box) {
        $stmtFlavors->execute(['id' => $box['id']]);
        $box['flavors'] = array_column($stmtFlavors->fetchAll(PDO::FETCH_ASSOC), 'name');
    }

    // Envoi final du JSON
    ob_end_clean(); // nettoie tout ce qui serait sorti avant par erreur
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($boxes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {

    ob_end_clean(); // Empêche tout texte parasite avant l'erreur JSON
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
