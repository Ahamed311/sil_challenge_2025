<?php
require_once "../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

if(
    empty($data['nom']) || empty($data['prenom']) || empty($data['email']) ||
    empty($data['mot_de_passe']) || empty($data['specialite']) || empty($data['date_embauche'])
){
    echo json_encode(["success"=>false, "message"=>"Tous les champs sont obligatoires"]);
    exit;
}

try {
    // 1️⃣ Vérification email déjà existant
    $stmtCheck = $pdo->prepare("SELECT id_user FROM users WHERE email = ?");
    $stmtCheck->execute([$data['email']]);
    if($stmtCheck->rowCount() > 0){
        echo json_encode(["success"=>false, "message"=>"Email déjà utilisé"]);
        exit;
    }

    // 2️⃣ Insert dans users
    $stmt = $pdo->prepare("
        INSERT INTO users (nom, prenom, email, mot_de_passe, role, statut)
        VALUES (?, ?, ?, ?, 'FORMATEUR', 'ACTIF')
    ");
    $stmt->execute([
        $data['nom'],
        $data['prenom'],
        $data['email'],
        password_hash($data['mot_de_passe'], PASSWORD_DEFAULT)
    ]);

    $id_user = $pdo->lastInsertId();

    // 3️⃣ Insert dans formateur
    $stmt2 = $pdo->prepare("
        INSERT INTO formateur (id_formateur, specialite, date_embauche)
        VALUES (?, ?, ?)
    ");
    $stmt2->execute([
        $id_user,
        $data['specialite'],
        $data['date_embauche']
    ]);

    echo json_encode(["success"=>true, "message"=>"Formateur créé avec succès"]);

} catch(Exception $e){
    echo json_encode([
        "success"=>false,
        "message"=>"Erreur : " . $e->getMessage()
    ]);
}
