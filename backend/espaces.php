<?php
header("Content-Type: application/json");
require_once "db.php";

$sql = "SELECT * FROM espaces_pedagogiques";
$stmt = $pdo->query($sql);
$espaces = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($espaces);
