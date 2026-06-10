<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once 'config/database.php'; 

$erro_msg = "";
$sucesso_msg = "";

// --- PROCESSAR EXCLUSÃO DE BLOCO ---
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    try {
        $stmt = $conn->prepare("DELETE FROM blocos WHERE id_bloco = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: gerenciar_blocos.php?msg=excluido");
        exit();
    } catch (mysqli_sql_exception $e) {
        $erro_msg = "Não é possível excluir: existem ambientes ou chamados vinculados a este bloco.";
    }
}

// --- PROCESSAR CADASTRO E EDIÇÃO ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Editar Bloco
    if (isset($_POST['editar_bloco'])) {
        $id = intval($_POST['id_bloco']);
        $nome = trim($_POST['nome']);

        if ($id > 0 && !empty($nome)) {
            try {
                $stmt = $conn->prepare("UPDATE blocos SET nome=? WHERE id_bloco=?");
                $stmt->bind_param("si", $nome, $id);
                
                if ($stmt->execute()) {
                    header("Location: gerenciar_blocos.php?msg=editado");
                    exit();
                }
            } catch (Exception $e) {
                $erro_msg = "Erro ao atualizar: " . $e->getMessage();
            }
        }
    }

    // Salvar Novo Bloco
    if (isset($_POST['salvar_bloco'])) {
        $nome = trim($_POST['nome']);

        if (!empty($nome)) {
            try {
                $stmt = $conn->prepare("INSERT INTO blocos (nome) VALUES (?)");
                $stmt->bind_param("s", $nome);
                $stmt->execute();
                header("Location: gerenciar_blocos.php?msg=cadastrado");
                exit();
            } catch (Exception $e) {
                $erro_msg = "Erro ao cadastrar: " . $e->getMessage();
            }
        }
    }
}

$sql = "SELECT * FROM blocos ORDER BY id_bloco DESC"; 
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM | Gerenciar Blocos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #f3f4f6; 
            font-family: 'Inter', sans-serif;
            color: #1f2937;
        }
        
        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 15px 0;
        }
        .brand-logo {
            font-size: 1.25rem;
            color: #0f172a;
            text-decoration: none;
        }
        
        .card-custom { 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            background-color: #ffffff;
            padding: 30px;
        }
        
        .btn-main { 
            background-color: #0d6efd; 
            color: white; 
            border-radius: 12px; 
            border: none; 
            font-weight: 500;
            transition: all 0.2s ease; 
        }
        .btn-main:hover { 
            background-color: #0b5ed7; 
            color: white;
            transform: translateY(-1px);
        }
        .btn-action {
            border-radius: 10px;
            padding: 6px 12px;
            transition: all 0.2s ease;
        }
        
        .table-custom th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 2px solid #f3f4f6;
            padding: 16px;
        }
        .table-custom td {
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.9rem;
        }
        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .id-badge {
            background-color: #f1f5f9;
            color: #475569;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom mb-5 shadow-sm">
        <div class="container px-4">
            <a class="brand-logo fw-bold" href="gestor_dashboard.php">
                <span style="color: #0d6efd;">SGM</span> | Gestão
            </a>
            <div class="d-flex align-items-center">
                <a href="gestor_dashboard.php" class="btn btn-outline-secondary btn-sm btn-action me-2">
                    <i class="bi bi-arrow-left me-1"></i> Painel Geral
                </a>
                <a href="logout.php" class="btn btn-sm btn-outline-danger btn-action">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <?php if($erro_msg): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 15px;" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-3 text-danger"></i>
                    <div><?php echo $erro_msg; ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 15px;" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-5 me-3 text-success"></i>
                    <div>Ação executada com sucesso!</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row align-items-center mb-4">
            <div class="col-sm-6">
                <h3 class="fw-bold m-0" style="color: #0f172a;">Estrutura de Blocos</h3>
                <p class="text-muted small m-0">Cadastre e gerencie os blocos físicos da instituição.</p>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <button class="btn btn-main px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovo">
                    <i class="bi bi-plus-lg me-2"></i>Novo Bloco
                </button>
            </div>
        </div>

        <div class="card card-custom">
            <div class="table-responsive">
                <table class="table table-custom align-middle m-0">
                    <thead>
                        <tr>
                            <th width="15%">ID</th>
                            <th width="70%">Nome do Bloco</th>
                            <th width="15%" class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($res->num_rows == 0): ?>
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="bi bi-building fs-2 d-block mb-2"></i>
                                    Nenhum bloco cadastrado até o momento.
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><span class="id-badge">#<?php echo $row['id_bloco']; ?></span></td>
                            <td><span class="fw-semibold text-dark"><i class="bi bi-building me-2 text-primary"></i><?php echo htmlspecialchars($row['nome']); ?></span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary btn-action me-1" data-bs-toggle="modal" data-bs-target="#modalEditar<?php echo $row['id_bloco']; ?>" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="javascript:if(confirm('Tem certeza de que deseja remover este bloco?')) { window.location.href='gerenciar_blocos.php?excluir=<?php echo $row['id_bloco']; ?>'; }" class="btn btn-sm btn-outline-danger btn-action" title="Excluir">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditar<?php echo $row['id_bloco']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                    <form method="POST">
                                        <input type="hidden" name="id_bloco" value="<?php echo $row['id_bloco']; ?>">
                                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                                            <h5 class="fw-bold">Editar Cadastro <span class="text-primary">#<?php echo $row['id_bloco']; ?></span></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body px-4">
                                            <div class="mb-2">
                                                <label class="form-label small fw-medium text-secondary">Identificação do Bloco</label>
                                                <input type="text" name="nome" class="form-control form-control-lg" style="border-radius: 12px; font-size:0.95rem;" value="<?php echo htmlspecialchars($row['nome']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-3 pb-4 px-4 d-flex justify-content-between">
                                            <button type="button" class="btn btn-light px-4 py-2" style="border-radius: 12px;" data-bs-dismiss="modal">Fechar</button>
                                            <button type="submit" name="editar_bloco" class="btn btn-primary px-4 py-2" style="border-radius: 12px;">Salvar Alterações</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNovo" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <form method="POST">
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h5 class="fw-bold">Inserir Novo Bloco</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="mb-2">
                            <label class="form-label small fw-medium text-secondary">Nome do Bloco</label>
                            <input type="text" name="nome" class="form-control form-control-lg" style="border-radius: 12px; font-size: 0.95rem;" placeholder="Ex: Bloco A" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-3 pb-4 px-4">
                        <button type="submit" name="salvar_bloco" class="btn btn-primary w-100 py-2.5 shadow-sm" style="border-radius: 12px; font-weight: 500;">Salvar Bloco no Sistema</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>