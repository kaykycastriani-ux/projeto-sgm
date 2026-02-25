<?php
session_start();
// Conexão com o banco de dados
require_once 'config/database.php'; 

// 1. Consulta para Novas Solicitações (Status: aberto)
$sql_novos = "SELECT COUNT(*) as total FROM chamados WHERE status = 'aberto'";
$res_novos = $conn->query($sql_novos);
$total_novos = $res_novos->fetch_assoc()['total'];

// 2. Consulta para Em Andamento (Status: em_execucao ou agendado)
$sql_andamento = "SELECT COUNT(*) as total FROM chamados WHERE status IN ('em_execucao', 'agendado')";
$res_andamento = $conn->query($sql_andamento);
$total_andamento = $res_andamento->fetch_assoc()['total'];

// 3. Consulta para Críticos (Prioridade: urgente ou alta)
$sql_criticos = "SELECT COUNT(*) as total FROM chamados WHERE prioridade IN ('urgente', 'alta') AND status != 'fechado'";
$res_criticos = $conn->query($sql_criticos);
$total_criticos = $res_criticos->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM | Dashboard Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        
        /* Navbar estilizada */
        .navbar { background-color: #1a1d21 !important; border-bottom: 3px solid #0d6efd; }
        
        /* Cards com Gradiente e Efeitos */
        .card-custom {
            border: none;
            border-radius: 15px;
            color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
            min-height: 140px;
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .card-custom i {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 5rem;
            opacity: 0.2;
        }

        .count-number { font-size: 3rem; font-weight: 800; }
        .card-label { font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }

        /* Cores específicas */
        .bg-gradient-success { background: linear-gradient(45deg, #198754, #2ecc71); }
        .bg-gradient-warning { background: linear-gradient(45deg, #f39c12, #f1c40f); }
        .bg-gradient-danger { background: linear-gradient(45deg, #d63031, #ff7675); }

        .btn-main {
            background-color: #1a1d21;
            color: white;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }
        .btn-main:hover { background-color: #0d6efd; color: white; transform: scale(1.05); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-shield-check-fill text-primary me-2"></i>SGM | Gestão Administrativa
            </a>
            <div class="d-flex align-items-center">
                <span class="navbar-text text-white me-3 d-none d-md-inline">Olá, <strong>Admin</strong> |</span>
                <a href="api/logout.php" class="btn btn-outline-danger btn-sm rounded-pill">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-dark">Fila de Trabalho</h1>
            <p class="text-muted">Acompanhamento de chamados em tempo real</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-custom bg-gradient-success p-4 shadow-sm">
                    <div class="card-label">Novas Solicitações</div>
                    <div class="count-number"><?php echo $total_novos; ?></div>
                    <i class="bi bi-plus-circle-fill"></i>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom bg-gradient-warning p-4 shadow-sm text-dark">
                    <div class="card-label">Em Andamento</div>
                    <div class="count-number text-white"><?php echo $total_andamento; ?></div>
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom bg-gradient-danger p-4 shadow-sm">
                    <div class="card-label">Críticos</div>
                    <div class="count-number"><?php echo $total_criticos; ?></div>
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-5">
            <a href="gestor_chamados.php" class="btn btn-main shadow">
                <i class="bi bi-list-task me-2"></i> Gerenciar Chamados
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>