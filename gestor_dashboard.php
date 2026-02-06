<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">SGM | Gestão Administrativa</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
        </li>
        </ul>
      <form class="d-flex" role="search">
        <span class="navbar-text">
        Olá Admin |
        </span>
        <a href="api/logout.php" class="btn btn-outline-danger"><i class="bi bi-box-arrow-right"></i>Sair</a>
      </form>
    </div>
  </div>
</nav>

<div class="container m-3">
    <div class="d-flex justify-content-center">
        <h1>Fila de Trabalho</h1>
    </div>
</div>

<div class="container mt-4">
  <div class="row">
    
    <div class="col-4">
      <div class="card bg-success text-white p-3">
        <h6>Novas Solicitações</h6>
        <h1>0</h1>
      </div>
    </div>

    <div class="col-4">
      <div class="card bg-warning text-white p-3">
        <h6>Em Andamento</h6>
        <h1>0</h1>
      </div>
    </div>

    <div class="col-4">
      <div class="card bg-danger text-white p-3">
        <h6>Críticos  </h6>
        <h1>0</h1>
      </div>
    </div>

  </div>
</div>

<div class="conteiner m-3">
    <div class="d-flex justify-content-center p-3">
        <button type="button" class="btn btn-secondary m-3"><i class="bi bi-list-ul"></i>Gerenciar Chamados</button>
        <button type="button" class="btn btn-outline-primary m-3">Configurar Ambientes</button>
    </div>
</div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>