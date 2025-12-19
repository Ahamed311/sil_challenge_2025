<?php
session_start(); // 🔴 OBLIGATOIRE : tout en haut

require_once "../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['email']) || empty($data['mot_de_passe'])) {
    echo json_encode([
        "success" => false,
        "message" => "Tous les champs sont obligatoires"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            "success" => false,
            "message" => "Utilisateur introuvable"
        ]);
        exit;
    }

    if (!password_verify($data['mot_de_passe'], $user['mot_de_passe'])) {
        echo json_encode([
            "success" => false,
            "message" => "Mot de passe incorrect"
        ]);
        exit;
    }

    // ✅ CONNEXION RÉUSSIE → CRÉATION SESSION
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['role'] = $user['role'];

    echo json_encode([
        "success" => true,
        "message" => "Connexion réussie",
        "role" => $user['role']
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur : " . $e->getMessage()
    ]);
}
