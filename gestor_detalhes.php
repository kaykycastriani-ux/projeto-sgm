<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM | Dashboard Gestor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .card-stats { border: none; border-radius: 12px; transition: transform 0.2s; }
        .card-stats:hover { transform: translateY(-5px); }
        .icon-shape { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
        .navbar-brand { font-weight: bold; }
        .table-card { border: none; border-radius: 12px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-shield-check me-2"></i>SGM | Gestor</a>
        <div class="ms-auto d-flex align-items-center">
            <span class="navbar-text text-light me-3 d-none d-md-inline">Olá, <strong>Administrador</strong></span>
            <a href="api/logout.php" class="btn btn-sm btn-outline-danger">Sair</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Visão Geral</h2>
            <p class="text-muted">Resumo estatístico do sistema de manutenção.</p>
        </div>
        <div>
            <a href="novo_chamado.php" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Abrir Chamado
            </a>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="card card-stats shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-primary text-white me-3">
                        <i class="bi bi-list-task fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Total</h6>
                        <h4 class="fw-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats shadow-sm p-3 border-start border-warning border-4">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-warning text-dark me-3">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Abertos</h6>
                        <h4 class="fw-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats shadow-sm p-3 border-start border-info border-4">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-info text-white me-3">
                        <i class="bi bi-gear-wide-connected fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Em Andamento</h6>
                        <h4 class="fw-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats shadow-sm p-3 border-start border-success border-4">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-success text-white me-3">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Concluídos</h6>
                        <h4 class="fw-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Últimas Solicitações</h5>
            <a href="todos_chamados.php" class="btn btn-sm btn-link text-decoration-none">Ver todos</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Data</th>
                        <th>Local</th>
                        <th>Problema</th>
                        <th>Prioridade</th>
                        <th>Status</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4 text-muted fw-bold">#128</td>
                        <td>15/10/2023</td>
                        <td>Bloco A - Sala 10</td>
                        <td>Ar condicionado não gela</td>
                        <td><span class="badge bg-danger">Alta</span></td>
                        <td><span class="badge bg-warning text-dark">Aberto</span></td>
                        <td class="text-center">
                            <a href="detalhes_chamado.php?id=128" class="btn btn-sm btn-outline-dark">Detalhes</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4 text-muted fw-bold">#127</td>
                        <td>14/10/2023</td>
                        <td>Recepção</td>
                        <td>Troca de lâmpadas</td>
                        <td><span class="badge bg-secondary">Baixa</span></td>
                        <td><span class="badge bg-info text-white">Em Execução</span></td>
                        <td class="text-center">
                            <a href="detalhes_chamado.php?id=127" class="btn btn-sm btn-outline-dark">Detalhes</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>