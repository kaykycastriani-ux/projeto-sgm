<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// Busca os ambientes reais diretamente cadastrados no banco
$query_ambientes = "SELECT id_ambiente, nome FROM ambientes ORDER BY nome ASC";
$resultado_ambientes = $conn->query($query_ambientes);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM | Novo Chamado</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f1f5f9; font-family: 'Plus Jakarta Sans', sans-serif; }
        .card-form { border: none; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; }
        .form-control, .form-select { border-radius: 10px; padding: 12px; border: 1px solid #e2e8f0; }
        .form-control:focus { box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); border-color: #3b82f6; }
        .btn-primary { background-color: #0f172a; border: none; border-radius: 12px; padding: 12px 25px; font-weight: 600; transition: 0.3s; }
        .btn-primary:hover { background-color: #3b82f6; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <a href="gestor_dashboard.php" class="text-decoration-none text-muted mb-4 d-inline-block">
                <i class="bi bi-arrow-left me-2"></i>Voltar ao Painel
            </a>

            <div class="card card-form p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="icon-badge mb-3">
                            <i class="bi bi-pencil-square text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h2 class="fw-800">Nova Solicitação</h2>
                        <p class="text-muted">Preencha os detalhes para a equipe técnica</p>
                    </div>
                    
                    <form action="processa_novo_chamado.php" method="POST" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Local / Bloco</label>
                                <select class="form-select" name="id_ambiente" required>
                                    <option value="" selected disabled>Selecione...</option>
                                    <?php 
                                    if ($resultado_ambientes && $resultado_ambientes->num_rows > 0) {
                                        while($ambiente = $resultado_ambientes->fetch_assoc()) {
                                            echo "<option value='".$ambiente['id_ambiente']."'>".$ambiente['nome']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Prioridade</label>
                                <select class="form-select" name="prioridade" required>
                                    <option value="baixa">🟢 Baixa</option>
                                    <option value="media" selected>🟡 Média</option>
                                    <option value="alta">🟠 Alta</option>
                                    <option value="urgente">🔴 Urgente</option>
                                </select>
                            </div>

                            <input type="hidden" name="id_tipo_servico" value="2">

                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase">Assunto</label>
                                <input type="text" class="form-control" name="assunto" placeholder="Resuma o problema" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase">Descrição Detalhada</label>
                                <textarea class="form-control" name="descricao" rows="4" placeholder="O que aconteceu?" required></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 shadow">
                                    <i class="bi bi-check2-circle me-2"></i>Confirmar e Abrir Chamado
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>