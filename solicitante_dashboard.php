<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM | Solicitante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card-table { border-radius: 10px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; }
    
        .bg-black { background-color: #000000 !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-person-workspace me-2"></i>SGM | Solicitante</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto align-items-center">
                <span class="navbar-text me-3 text-light">
                    Olá, <strong>Solicitante</strong>
                </span>
                <a href="api/logout.php" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-box-arrow-right me-1"></i>Sair
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Minhas Solicitações</h3>
            <p class="text-muted">Acompanhe o status dos seus chamados abertos.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="#" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-circle me-2"></i>Nova Solicitação
            </a>
        </div>
    </div>

    <div class="card card-table overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Local</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#01</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt me-2 text-secondary"></i>
                                Bloco Admin
                            </div>
                        </td>
                        <td>Vazamento na pia da recepção</td>
                        <td>
                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger">
                                <i class="bi bi-dot"></i> FECHADO
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detalhes
                            </button>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#02</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt me-2 text-secondary"></i>
                                Laboratório 03
                            </div>
                        </td>
                        <td>Ar-condicionado não liga</td>
                        <td>
                            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning">
                                <i class="bi bi-dot"></i> EM ANÁLISE
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detalhes
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <a href="dashboard.php" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left"></i> Voltar ao início
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>