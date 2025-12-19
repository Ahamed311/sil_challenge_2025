<?php
require_once "../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

if(empty($data['annee_academique'])){
    echo json_encode(["success"=>false,"message"=>"Année académique obligatoire"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO promotion (annee_academique, date_creation)
        VALUES (?, NOW())
    ");
    $stmt->execute([$data['annee_academique']]);

    echo json_encode(["success"=>true,"message"=>"Promotion créée avec succès"]);
} catch(Exception $e){
    echo json_encode(["success"=>false,"message"=>"Erreur lors de la création de la promotion"]);
}
