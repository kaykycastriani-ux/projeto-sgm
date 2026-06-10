<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['id_usuario'] = 1;
$_SESSION['nome'] = "Administrador";
$_SESSION['perfil'] = "gestor";

require_once 'config/database.php';

// --- PROCESSAR EXCLUSÃO DE CHAMADO ---
if (isset($_GET['excluir_chamado'])) {
    $id_excluir = intval($_GET['excluir_chamado']);
    if ($id_excluir > 0) {
        try {
            $conn->query("DELETE FROM chamados_anexos WHERE id_chamado = $id_excluir");
            $conn->query("DELETE FROM chamados WHERE id_chamado = $id_excluir");
            header("Location: gestor_dashboard.php?msg=excluido");
            exit;
        } catch (Exception $e) {
            header("Location: gestor_dashboard.php?msg=erro_excluir");
            exit;
        }
    }
}

// --- PROCESSAR ALTERAÇÃO DE STATUS DIRETO PELA TABELA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar_status_id'])) {
    $id_alt = intval($_POST['alterar_status_id']);
    $novo_status = mysqli_real_escape_string($conn, $_POST['novo_status_valor']);
    
    $conn->query("UPDATE chamados SET status = '$novo_status' WHERE id_chamado = $id_alt");
    header("Location: gestor_dashboard.php");
    exit;
}

// 1. CARD 1: Total Geral de Chamados Criados
$total_novos = 0;
$res_novos = $conn->query("SELECT COUNT(*) as total FROM chamados");
if ($res_novos) { $total_novos = $res_novos->fetch_assoc()['total']; }

// 2. CARD 2: Contadores de Status Adaptativos (Garantem leitura independente de como foi salvo antes)
$total_aberto = 0;
$res_aberto = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE LOWER(status) LIKE '%abert%'");
if ($res_aberto) { $total_aberto = $res_aberto->fetch_assoc()['total']; }

$total_andamento = 0; 
$res_andamento = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE LOWER(status) LIKE '%execu%' OR LOWER(status) LIKE '%andament%'");
if ($res_andamento) { $total_andamento = $res_andamento->fetch_assoc()['total']; }

$total_agendado = 0;
$res_agendado = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE LOWER(status) LIKE '%agend%'");
if ($res_agendado) { $total_agendado = $res_agendado->fetch_assoc()['total']; }

$total_concluido = 0;
$res_concluido = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE LOWER(status) LIKE '%conclui%'");
if ($res_concluido) { $total_concluido = $res_concluido->fetch_assoc()['total']; }

$total_fechado = 0;
$res_fechado = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE LOWER(status) LIKE '%fechad%'");
if ($res_fechado) { $total_fechado = $res_fechado->fetch_assoc()['total']; }

$total_cancelado = 0;
$res_cancelado = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE LOWER(status) LIKE '%cancel%'");
if ($res_cancelado) { $total_cancelado = $res_cancelado->fetch_assoc()['total']; }

// 3. CARD 3: Contadores de Prioridades Adaptativos
$total_baixa = 0; 
$res_baixa = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE LOWER(prioridade) LIKE '%baix%'"); 
if ($res_baixa) { $total_baixa = $res_baixa->fetch_assoc()['total']; }

$total_media = 0; 
$res_media = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE LOWER(prioridade) LIKE '%medi%'"); 
if ($res_media) { $total_media = $res_media->fetch_assoc()['total']; }

$total_alta = 0; 
$res_alta = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE LOWER(prioridade) LIKE '%alt%'"); 
if ($res_alta) { $total_alta = $res_alta->fetch_assoc()['total']; }

$total_urgente = 0; 
$res_urgente = $conn->query("SELECT COUNT(*) as total FROM chamados WHERE LOWER(prioridade) LIKE '%urgent%'"); 
if ($res_urgente) { $total_urgente = $res_urgente->fetch_assoc()['total']; }

// --- BUSCA DINÂMICA DE CHAMADOS REAIS PARA A TABELA ---
$resultado_lista = $conn->query("SELECT c.*, a.nome as ambiente FROM chamados c LEFT JOIN ambientes a ON c.id_ambiente = a.id_ambiente ORDER BY c.id_chamado DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM | Dashboard Pro</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-blue: #3b82f6;
            --glass-bg: rgba(255, 255, 255, 0.8);
        }

        body { 
            background: #f1f5f9; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        .navbar { 
            background: var(--glass-bg) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .navbar-brand { color: var(--primary-dark) !important; letter-spacing: -0.5px; }

        .card-stat {
            border: none;
            border-radius: 24px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .card-stat:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }

        .card-stat .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: rgba(255,255,255,0.2);
        }

        .stat-value { font-size: 2.5rem; font-weight: 800; line-height: 1; }
        .stat-label { font-size: 0.85rem; font-weight: 600; opacity: 0.9; text-transform: uppercase; }

        .bg-new { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .bg-pending { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
        .bg-urgent { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }

        .dropdown-menu-card {
            background: rgba(15, 23, 42, 0.95);
            border: none;
            border-radius: 12px;
        }
        .dropdown-menu-card .dropdown-item {
            color: #f1f5f9;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .dropdown-menu-card .dropdown-item:hover {
            background: #3b82f6;
            color: white;
        }

        .btn-action {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            text-align: left;
            transition: all 0.2s ease;
            text-decoration: none;
            color: #475569;
            display: block;
            height: 100%;
        }

        .btn-action:hover { background: var(--primary-dark); color: white !important; border-color: var(--primary-dark); }
        .btn-action i { font-size: 1.5rem; margin-bottom: 10px; display: block; color: var(--accent-blue); }
        .btn-action:hover i { color: #fff; }
        .section-title { position: relative; padding-bottom: 10px; margin-bottom: 30px; font-weight: 700; }
        .section-title::after { content: ''; position: absolute; left: 50%; transform: translateX(-50%); bottom: 0; width: 50px; height: 4px; background: var(--accent-blue); border-radius: 10px; }
        
        .table-card { border: none; border-radius: 16px; background: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <span class="text-primary">SGM</span> | Gestão
        </a>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <small class="text-muted d-block">Bem-vindo,</small>
                <span class="fw-bold"><?php echo $_SESSION['nome']; ?></span>
            </div>
            <a href="#" class="btn btn-light rounded-circle shadow-sm">
                <i class="bi bi-person-circle"></i>
            </a>
        </div>
    </div>
</nav>

<div class="container py-4">
    
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'excluido'): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 15px;" role="alert">
            <i class="bi bi-trash3-fill me-2 text-success"></i> Chamado removido com sucesso do sistema!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'erro_excluir'): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 15px;" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> Erro ao tentar remover chamado do banco.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="text-center mb-5">
        <h2 class="fw-800">Painel de Controle</h2>
        <p class="text-muted">Gestão centralizada de solicitações e recursos</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12 col-md-4">
            <div class="card-stat bg-new">
                <div class="icon-box"><i class="bi bi-lightning-charge"></i></div>
                <div class="stat-label">Total de Chamados Criados</div>
                <div class="stat-value"><?php echo $total_novos; ?></div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card-stat bg-pending">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-box dropdown" style="cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-hourglass-split"></i>
                        <ul class="dropdown-menu dropdown-menu-card shadow">
                            <li><a class="dropdown-item" href="#" onclick="mudarCardStatus('Aberto', <?php echo $total_aberto; ?>)">🟢 Aberto</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mudarCardStatus('Execução', <?php echo $total_andamento; ?>)">⏳ Execução</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mudarCardStatus('Agendado', <?php echo $total_agendado; ?>)">📅 Agendado</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mudarCardStatus('Concluído', <?php echo $total_concluido; ?>)">✅ Concluído</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mudarCardStatus('Fechados', <?php echo $total_fechado; ?>)">🔒 Fechados</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mudarCardStatus('Cancelado', <?php echo $total_cancelado; ?>)">❌ Cancelado</a></li>
                        </ul>
                    </div>
                    <small class="text-white-50 fw-bold" style="font-size: 0.75rem;">Clique no ícone ⚙️</small>
                </div>
                <div class="stat-label" id="label-status">Execução</div>
                <div class="stat-value" id="valor-status"><?php echo $total_andamento; ?></div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="card-stat bg-urgent">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-box dropdown" style="cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-exclamation-octagon"></i>
                        <ul class="dropdown-menu dropdown-menu-card shadow">
                            <li><a class="dropdown-item" href="#" onclick="mudarCardPrioridade('baixa', <?php echo $total_baixa; ?>)">🟢 Baixa</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mudarCardPrioridade('media', <?php echo $total_media; ?>)">🟡 Média</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mudarCardPrioridade('alta', <?php echo $total_alta; ?>)">🟠 Alta</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mudarCardPrioridade('urgente', <?php echo $total_urgente; ?>)">🔴 Urgente</a></li>
                        </ul>
                    </div>
                    <small class="text-white-50 fw-bold" style="font-size: 0.75rem;">Clique no ícone ⚙️</small>
                </div>
                <div class="stat-label" id="label-prioridade">Urgentes</div>
                <div class="stat-value" id="valor-prioridade"><?php echo $total_urgente; ?></div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 mb-3">
        <h4 class="section-title">Controle Operacional de Chamados</h4>
    </div>

    <div class="card table-card p-3 mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Ambiente / Local</th>
                        <th>Descrição</th>
                        <th>Prioridade</th>
                        <th class="text-end">Mudar Status / Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($resultado_lista && $resultado_lista->num_rows > 0) {
                        while($linha = $resultado_lista->fetch_assoc()) {
                            $desc = isset($linha['descricao_problema']) ? $linha['descricao_problema'] : (isset($linha['descricao']) ? $linha['descricao'] : 'Sem descrição');
                            
                            $prio_raw = strtolower($linha['prioridade']);
                            if ($prio_raw == 'media') $prio_display = 'Média';
                            else $prio_display = ucfirst($prio_raw);

                            echo "<tr>";
                            echo "<td class='fw-bold'>#".$linha['id_chamado']."</td>";
                            echo "<td><i class='bi bi-geo-alt text-primary me-1'></i>".($linha['ambiente'] ?? 'Não definido')."</td>";
                            echo "<td class='text-muted'>".substr($desc, 0, 45)."...</td>";
                            echo "<td><span class='badge bg-light text-dark border'>".$prio_display."</span></td>";
                            echo "<td>";
                            
                            echo "<div class='d-flex justify-content-end gap-2'>";
                            echo "<form action='' method='POST' class='d-flex gap-1'>";
                            echo "<input type='hidden' name='alterar_status_id' value='".$linha['id_chamado']."'>";
                            echo "<select name='novo_status_valor' class='form-select form-select-sm' style='max-width: 140px; font-size: 0.85rem;'>";
                            
                            $st_banco = isset($linha['status']) ? trim(mb_strtolower($linha['status'], 'UTF-8')) : '';
                            
                            $is_aberto     = ($st_banco === 'aberto');
                            $is_execucao   = ($st_banco === 'execução' || $st_banco === 'execucao' || $st_banco === 'em execução' || $st_banco === 'em_execucao');
                            $is_agendado   = ($st_banco === 'agendado');
                            $is_concluido  = ($st_banco === 'concluído' || $st_banco === 'concluido' || $st_banco === 'concluídos' || $st_banco === 'concluidos');
                            $is_fechados   = ($st_banco === 'fechados' || $st_banco === 'fechado');
                            $is_cancelado  = ($st_banco === 'cancelado');

                            echo "<option value='Aberto'".($is_aberto ? ' selected' : '').">🟢 Aberto</option>";
                            echo "<option value='Execução'".($is_execucao ? ' selected' : '').">⏳ Execução</option>";
                            echo "<option value='Agendado'".($is_agendado ? ' selected' : '').">📅 Agendado</option>";
                            echo "<option value='Concluído'".($is_concluido ? ' selected' : '').">✅ Concluído</option>";
                            echo "<option value='Fechados'".($is_fechados ? ' selected' : '').">🔒 Fechados</option>";
                            echo "<option value='Cancelado'".($is_cancelado ? ' selected' : '').">❌ Cancelado</option>";
                            
                            echo "</select>";
                            echo "<button type='submit' class='btn btn-sm btn-dark' title='Confirmar Alteração'><i class='bi bi-check-lg'></i></button>";
                            echo "</form>";
                            
                            echo "<a href=\"javascript:if(confirm('Deseja realmente excluir permanentemente o chamado #".$linha['id_chamado']."?')) { window.location.href='gestor_dashboard.php?excluir_chamado=".$linha['id_chamado']."'; }\" class='btn btn-sm btn-outline-danger' title='Excluir Chamado'>";
                            echo "<i class='bi bi-trash3'></i>";
                            echo "</a>";
                            echo "</div>";
                            
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center text-muted py-5'><i class='bi bi-ticket-detailed fs-3 d-block mb-2'></i>Nenhum chamado registrado no banco de dados.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center">
        <h4 class="section-title">Ações Rápidas</h4>
    </div>
    
    <div class="row g-3">
        <div class="col-6 col-md-4 col-lg-2.4" style="flex: 1 0 18%;">
            <a href="gestor_chamados.php" class="btn-action shadow-sm">
                <i class="bi bi-ticket-perforated"></i>
                <span class="fw-bold">Chamados</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2.4" style="flex: 1 0 18%;">
            <a href="gerenciar_blocos.php" class="btn-action shadow-sm">
                <i class="bi bi-building"></i>
                <span class="fw-bold">Blocos</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2.4" style="flex: 1 0 18%;">
            <a href="gerenciar_usuarios.php" class="btn-action shadow-sm">
                <i class="bi bi-person-gear"></i>
                <span class="fw-bold">Usuários</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2.4" style="flex: 1 0 18%;">
            <a href="ambientes_gestor.php" class="btn-action shadow-sm">
                <i class="bi bi-geo-alt"></i>
                <span class="fw-bold">Ambientes</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2.4" style="flex: 1 0 18%;">
            <a href="tipos_servicos.php" class="btn-action shadow-sm">
                <i class="bi bi-wrench-adjustable"></i>
                <span class="fw-bold">Serviços</span>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function mudarCardStatus(status, valor) {
    const label = document.getElementById('label-status');
    const valorContainer = document.getElementById('valor-status');
    valorContainer.innerText = valor;
    
    if(status === 'Aberto') label.innerText = "Aberto";
    if(status === 'Execução') label.innerText = "Execução";
    if(status === 'Agendado') label.innerText = "Agendado";
    if(status === 'Concluído') label.innerText = "Concluído";
    if(status === 'Fechados') label.innerText = "Fechados";
    if(status === 'Cancelado') label.innerText = "Cancelados";
}

function mudarCardPrioridade(tipo, valor) {
    const label = document.getElementById('label-prioridade');
    const valorContainer = document.getElementById('valor-prioridade');
    valorContainer.innerText = valor;
    
    if(tipo === 'baixa') label.innerText = "Baixa Prioridade";
    if(tipo === 'media') label.innerText = "Média Prioridade";
    if(tipo === 'alta') label.innerText = "Alta Prioridade";
    if(tipo === 'urgente') label.innerText = "Urgentes";
}
</script>
</body>
</html>