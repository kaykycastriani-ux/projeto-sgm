<?php
session_start();
require_once '../config/database.php'; // Ajuste o caminho conforme sua estrutura
header('Content-Type: application/json');

// 1. Verificação de Segurança
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}

// 2. Captura do filtro de status via URL (GET)
$status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';

// 3. Construção da cláusula WHERE
$whereClause = "";
if ($status !== '') {
    $whereClause = "WHERE c.status = '$status'";
}

// 4. SQL Completo
$sql = "SELECT c.id_chamado, c.descricao_problema, c.status, c.prioridade,
               c.data_abertura, a.nome as ambiente_nome, b.nome as bloco_nome,
               u.nome as solicitante_nome, t.nome as tecnico_nome
        FROM chamados c
        JOIN ambientes a ON c.id_ambiente = a.id_ambiente
        JOIN blocos b ON a.id_bloco = b.id_bloco
        JOIN usuarios u ON c.id_solicitante = u.id_usuario
        LEFT JOIN usuarios t ON c.id_tecnico = t.id_usuario
        $whereClause
        ORDER BY CASE WHEN c.prioridade = 'urgente' THEN 1
                      WHEN c.prioridade = 'alta' THEN 2
                      ELSE 3 END, c.data_abertura DESC";

$result = $conn->query($sql);

if (!$result) {
    // Se a query falhar, retorna o erro do MySQL para ajudar no debug
    echo json_encode(["error" => $conn->error]);
    exit;
}

// 5. Transformar resultado em Array e enviar como JSON
$chamados = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($chamados);