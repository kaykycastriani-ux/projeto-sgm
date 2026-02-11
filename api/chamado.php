<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$sql = "SELECT
           c.id_chamado AS id, 
            a.nome AS local_tipo, 
            c.prioridade, 
            c.descricao_problema AS Descrição,
            c.status
        FROM chamados c
        LEFT JOIN usuarios u1 ON c.id_solicitante = u1.id_usuario
        LEFT JOIN ambientes a ON c.id_ambiente = a.id_ambiente";

        $res = $conn-> query($sql);
        $dados = $res->fetch_assoc();
        echo json_encode($dados);