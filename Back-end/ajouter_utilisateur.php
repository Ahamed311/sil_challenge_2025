<?php
require_once "../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

if(
    empty($data['nom']) ||
    empty($data['prenom']) ||
    empty($data['email']) ||
    empty($data['mot_de_passe']) ||
    empty($data['date_naiss']) ||
    empty($data['role']) ||
    empty($data['statut'])
){
    echo json_encode([
        "success" => false,
        "message" => "Tous les champs sont obligatoires"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO users (nom, prenom, email, mot_de_passe, date_naiss, role, statut)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data['nom'],
        $data['prenom'],
        $data['email'],
        password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
        $data['date_naiss'],
        $data['role'],
        $data['statut']
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Utilisateur créé avec succès"
    ]);
} catch(Exception $e){
    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de la création de l'utilisateur"
    ]);
}
