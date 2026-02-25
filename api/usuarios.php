<?php
session_start();
require_once  '../config/database.php';
header('content-type: applocation/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}

$sql = "SELECT id_usuario, nome FROM usuario WHERE perfil = 'tecnico' and ativo = 1 order by nome asc";
$result = $conn->query($sql);
$tecnico = $result->fetch_all(MYSQLI_ASSOC);

ech json_encode($tecnicos);