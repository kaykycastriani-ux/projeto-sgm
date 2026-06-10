<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM | Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; }
   
        .main-content {
            min-height: 70vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">SGM | Técnico</a>
        <div class="ms-auto d-flex align-items-center">
            <span class="navbar-text text-white me-3">
                Olá, <strong>Técnico</strong> |
            </span>
            <a href="api/logout.php" class="btn btn-sm btn-outline-light">Sair</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold border-bottom pb-2">Minha Lista de Trabalho</h2>
        </div>
    </div>

    <div class="main-content">
        <h3 class="text-secondary mb-4">Nenhum Trabalho 🎉</h3>
        <a href="#" class="btn btn-dark btn-lg shadow-sm">
            <i class="bi bi-search me-2"></i>Procurar Novo Trabalho
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>