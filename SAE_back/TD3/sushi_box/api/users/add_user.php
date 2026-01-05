<?php   


               // Inscription
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // --- Connexion BDD ---
    $pdo = new PDO(
        "mysql:host=localhost;dbname=api;charset=utf8",
        "root",
        "root"
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- Vérification méthode ---
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception("Méthode non autorisée. Utilisez POST.");
    }

    // --- Lecture JSON ---
    $content = file_get_contents("php://input");
    $data = json_decode($content, true);

    if (!$data) {
        http_response_code(400);
        throw new Exception("JSON invalide ou non reçu.");
    }

    // --- Champs obligatoires ---
    $required = ['firstname', 'lastname', 'email', 'password'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            http_response_code(400);
            throw new Exception("Champ manquant : $field");
        }
    }


    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (firstname, lastname, email, password)
            VALUES (:firstname, :lastname, :email, :password)";

    $query = $pdo->prepare($sql);
    $query->execute([
        ':firstname' => $data['firstname'],
        ':lastname'  => $data['lastname'],
        ':email'     => $data['email'],
        ':password'  => $passwordHash
    ]);

    
    ob_end_clean();
    header('Content-Type: application/json');
    http_response_code(201);

    echo json_encode([
        'success' => true,
        'message' => 'Utilisateur créé avec succès'
    ]);

} catch (Exception $e) {
    ob_end_clean();
    header('Content-Type: application/json');
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
