<?php
session_start();

// AJUSTE ESTA LINHA: Verifique o caminho real do seu arquivo de banco
// Se a pasta config estiver dentro de sgm, use 'config/database.php'
// Se estiver fora, use '../config/database.php'
require_once 'config/database.php'; 

// 1. Captura o ID do chamado vindo da URL
$id_chamado = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_chamado === 0) {
    header("Location: gestor_chamados.php");
    exit;
}

// 2. Busca os dados reais (Usando as tabelas do seu banco: chamados, usuarios, ambientes, blocos)
$sql = "SELECT c.*, u.nome as solicitante_nome, a.nome as ambiente_nome, b.nome as bloco_nome
        FROM chamados c
        JOIN usuarios u ON c.id_solicitante = u.id_usuario
        JOIN ambientes a ON c.id_ambiente = a.id_ambiente
        JOIN blocos b ON a.id_bloco = b.id_bloco
        WHERE c.id_chamado = $id_chamado";

$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("Erro: Chamado não encontrado no banco de dados.");
}

$chamado = $result->fetch_assoc();

// 3. Busca as evidências na sua tabela 'chamados_anexos'
$sql_fotos = "SELECT caminho_arquivo FROM chamados_anexos WHERE id_chamado = $id_chamado";
$res_fotos = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Chamado #<?php echo $id_chamado; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .img-placeholder {
            width: 80px; height: 80px;
            border: 2px dashed #4d78b8ff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; border-radius: 8px;
        }
        .preview-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body class="bg-light">
    <main class="container py-5">
        
        <div class="mb-4">
            <a href="gestor_chamados.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <section class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <strong>Dados da Solicitação</strong>
                    </div>
                    <div class="card-body">
                        <h6 class="mt-3"><strong>Status: </strong> 
                            <span class="badge rounded text-bg-secondary"><?php echo strtoupper($chamado['status']); ?></span>
                        </h6>
                        <h6 class="mt-3"><strong>Descrição: </strong><?php echo $chamado['descricao_problema']; ?></h6>
                        <h6 class="mt-3"><strong>Local: </strong><?php echo $chamado['bloco_nome'] . " - " . $chamado['ambiente_nome']; ?></h6>
                        <h6 class="mt-3"><strong>Solicitante: </strong><?php echo $chamado['solicitante_nome']; ?></h6>
                        <h6 class="mt-3"><strong>Abertura: </strong><?php echo date('d/m/Y H:i:s', strtotime($chamado['data_abertura'])); ?></h6>
                    </div>
                    
                    <div class="card-footer bg-white py-3">
                        <h6><strong>Evidências:</strong></h6>
                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            
                            <?php if($res_fotos && $res_fotos->num_rows > 0): ?>
                                <?php while($foto = $res_fotos->fetch_assoc()): ?>
                                    <img src="uploads/<?php echo $foto['caminho_arquivo']; ?>" class="preview-img img-thumbnail shadow-sm">
                                <?php endwhile; ?>
                            <?php endif; ?>

                            <label for="upload-evidencia" class="img-placeholder">
                                <i class="bi bi-plus-lg text-muted" style="font-size: 1.5rem;"></i>
                            </label>
                            <input type="file" id="upload-evidencia" name="evidencias[]" style="display: none;" accept="image/*" multiple onchange="previewImages()">
                            
                            <div id="preview-container" class="d-flex gap-2 flex-wrap"></div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-warning mt-3 w-100 fw-bold">Reabrir chamado</button>
            </div>
        </section>
    </main>

    <script>
        function previewImages() {
            const container = document.getElementById('preview-container');
            const files = document.getElementById('upload-evidencia').files;
            if (files) {
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'preview-img img-thumbnail shadow-sm';
                        container.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
</body>
</html>