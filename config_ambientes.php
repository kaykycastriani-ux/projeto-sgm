<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once 'config/database.php'; 

$erro_msg = "";


if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    try {
        $stmt = $conn->prepare("DELETE FROM ambientes WHERE id_ambiente = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: ambientes_gestor.php?msg=excluido");
        exit();
    } catch (mysqli_sql_exception $e) {
        $erro_msg = "Não é possível excluir este ambiente pois existem chamados vinculados a ele.";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_ambiente'])) {
    $id = intval($_POST['id_ambiente']);
    $nome = trim($_POST['nome']);
    $id_bloco = intval($_POST['id_bloco']);

    if ($id > 0 && !empty($nome)) {
        $stmt = $conn->prepare("UPDATE ambientes SET nome = ?, id_bloco = ? WHERE id_ambiente = ?");
        $stmt->bind_param("sii", $nome, $id_bloco, $id);
        $stmt->execute();
        header("Location: ambientes_gestor.php?msg=editado");
        exit();
    }
}


$ambientes = $conn->query("SELECT a.*, b.nome as nome_bloco FROM ambientes a JOIN blocos b ON a.id_bloco = b.id_bloco ORDER BY a.nome ASC");
$blocos = $conn->query("SELECT * FROM blocos ORDER BY nome ASC"); // Para o select do modal
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SGM | Ambientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #1a1d21 !important; border-bottom: 3px solid #0d6efd; }
        .card-custom { border: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .table-dark-custom { background-color: #212529; color: white; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="#">SGM | Gestor</a>
            <div class="d-flex align-items-center text-white">
                <span class="me-3">Olá, Gestor |</span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Sair</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center text-secondary fw-bold mb-4">GERENCIAR AMBIENTES</h2>

        <?php if($erro_msg): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $erro_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-center gap-2 mb-4">
            <button class="btn btn-outline-secondary" onclick="window.location.reload()"><i class="bi bi-arrow-clockwise"></i> Atualizar Tabela</button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovo"><i class="bi bi-plus-circle"></i> Adicionar Ambiente</button>
        </div>

        <div class="card card-custom p-0 overflow-hidden">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nome do Ambiente</th>
                        <th>Bloco Pertencente</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $ambientes->fetch_assoc()): ?>
                    <tr>
                        <td class="ps-4 fw-bold">#<?php echo $row['id_ambiente']; ?></td>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row['nome_bloco']); ?></span></td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar<?php echo $row['id_ambiente']; ?>">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>
                            <a href="javascript:if(confirm('Excluir este ambiente?')) window.location.href='ambientes_gestor.php?excluir=<?php echo $row['id_ambiente']; ?>'" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Excluir
                            </a>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEditar<?php echo $row['id_ambiente']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST">
                                    <input type="hidden" name="id_ambiente" value="<?php echo $row['id_ambiente']; ?>">
                                    <div class="modal-header">
                                        <h5 class="fw-bold">Editar Ambiente</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Nome do Ambiente</label>
                                            <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($row['nome']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Bloco</label>
                                            <select name="id_bloco" class="form-select" required>
                                                <?php 
                                                $blocos->data_seek(0);
                                                while($b = $blocos->fetch_assoc()): 
                                                ?>
                                                    <option value="<?php echo $b['id_bloco']; ?>" <?php echo ($b['id_bloco'] == $row['id_bloco']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($b['nome']); ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="editar_ambiente" class="btn btn-primary w-100">Salvar Alterações</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle"></i> Voltar à Página Inicial</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>