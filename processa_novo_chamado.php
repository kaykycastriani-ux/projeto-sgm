<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/database.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php?erro=acesso_negado");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_solicitante = $_SESSION['id_usuario'];
    $id_ambiente    = intval($_POST['id_ambiente']);
    $id_tecnico     = !empty($_POST['id_tecnico']) ? intval($_POST['id_tecnico']) : null;
    $id_tipo        = intval($_POST['id_tipo_servico']);
    $descricao      = mysqli_real_escape_string($conn, $_POST['descricao']);
    $prioridade     = mysqli_real_escape_string($conn, $_POST['prioridade'] ?? 'media');

    $conn->begin_transaction();

    try {
        $sql_chamado = "INSERT INTO chamados 
            (descricao_problema, status, prioridade, id_solicitante, id_tecnico, id_ambiente, id_tipo_servico, data_abertura) 
            VALUES 
            (?, 'aberto', ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql_chamado);
        $stmt->bind_param("ssiiii", $descricao, $prioridade, $id_solicitante, $id_tecnico, $id_ambiente, $id_tipo);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao inserir chamado: " . $stmt->error);
        }

        $id_chamado = $conn->insert_id;

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $diretorio = "uploads/chamados/";
            
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }

            $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $nome_arquivo = time() . "_" . uniqid() . "." . $extensao;
            $caminho_final = $diretorio . $nome_arquivo;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_final)) {
                // ARRUMADO: Removido a coluna 'tipo_anexo' pois ela não existe no seu script SQL de criação da tabela chamados_anexos
                $sql_anexo = "INSERT INTO chamados_anexos (caminho_arquivo, id_chamado) VALUES (?, ?)";
                $stmt_anexo = $conn->prepare($sql_anexo);
                $stmt_anexo->bind_param("si", $caminho_final, $id_chamado);
                $stmt_anexo->execute();
            }
        }

        $conn->commit();
        
        // ARRUMADO: Retorna direto para o Dashboard para ver os números atualizados instantaneamente
        header("Location: gestor_dashboard.php?msg=sucesso");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        die("Erro ao processar: " . $e->getMessage());
    }
} else {
    header("Location: gestor_chamados.php");
    exit;
}
?>