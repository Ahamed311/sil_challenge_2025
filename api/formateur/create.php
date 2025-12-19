<?php
require_once "../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data['nom']) ||
    empty($data['prenom']) ||
    empty($data['email']) ||
    empty($data['mot_de_passe']) ||
    empty($data['specialite']) ||
    empty($data['date_embauche'])
) {
    echo json_encode([
        "success" => false,
        "message" => "Tous les champs sont obligatoires"
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    //  Vérifier si email existe
    $check = $pdo->prepare("SELECT id_user FROM users WHERE email = ?");
    $check->execute([$data['email']]);

    if ($check->rowCount() > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Email déjà utilisé"
        ]);
        exit;
    }

    //  Insertion USERS
    $stmtUser = $pdo->prepare("
        INSERT INTO users (nom, prenom, email, mot_de_passe, role, statut)
        VALUES (?, ?, ?, ?, 'FORMATEUR', 'ACTIF')
    ");

    $stmtUser->execute([
        $data['nom'],
        $data['prenom'],
        $data['email'],
        password_hash($data['mot_de_passe'], PASSWORD_DEFAULT)
    ]);

    $id_user = $pdo->lastInsertId();

    //  Insertion FORMATEUR
    $stmtFormateur = $pdo->prepare("
        INSERT INTO formateur (id_formateur, specialite, date_embauche)
        VALUES (?, ?, ?)
    ");

    $stmtFormateur->execute([
        $id_user,
        $data['specialite'],
        $data['date_embauche']
    ]);

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Formateur créé avec succès"
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        "success" => false,
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}
