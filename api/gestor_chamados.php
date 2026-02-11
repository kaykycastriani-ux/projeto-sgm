<?php 

session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

$sql = "SELECT chamados.id_chamado, 
        usuarios.nome, ambientes.nome, 
        chamados.prioridade, 
        chamados.status FROM chamados
        INNER JOIN usuarios ON id_solicitante = usuarios.id_usuario JOIN ambientes ON chamados.id_ambiente = ambientes.id_ambiente";

$res = $conn->query($sql);
$dados = $res->fetch_assoc();

echo json_encode($dados);