<?php
session_start();
require_once 'config/database.php';

$erro_msg = "";
$pagina_atual = basename($_SERVER['PHP_SELF']); 


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar_ambiente'])) {
    $nome = trim($_POST['nome']);
    $id_bloco = intval($_POST['id_bloco']);

    if (!empty($nome) && $id_bloco > 0) {
        $stmt = $conn->prepare("INSERT INTO ambientes (nome, id_bloco) VALUES (?, ?)");
        $stmt->bind_param("si", $nome, $id_bloco);
        
        if ($stmt->execute()) {
            header("Location: " . $pagina_atual . "?msg=cadastrado");
            exit();
        } else {
            $erro_msg = "Erro ao cadastrar ambiente: " . $conn->error;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_ambiente'])) {
    $id_ambiente = intval($_POST['id_ambiente']);
    $nome = trim($_POST['nome']);
    $id_bloco = intval($_POST['id_bloco']);

    if ($id_ambiente > 0 && !empty($nome) && $id_bloco > 0) {
        $stmt = $conn->prepare("UPDATE ambientes SET nome=?, id_bloco=? WHERE id_ambiente=?");
        $stmt->bind_param("sii", $nome, $id_bloco, $id_ambiente);
        
        if ($stmt->execute()) {
            header("Location: " . $pagina_atual . "?msg=editado");
            exit();
        } else {
            $erro_msg = "Erro ao atualizar ambiente: " . $conn->error;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_ambiente'])) {
    $id_ambiente_del = intval($_POST['id_ambiente_del']);
    
    if ($id_ambiente_del > 0) {
        try {
            $stmt_del = $conn->prepare("DELETE FROM ambientes WHERE id_ambiente=?");
            $stmt_del->bind_param("i", $id_ambiente_del);
            $stmt_del->execute();
            header("Location: " . $pagina_atual . "?msg=deletado");
            exit();
        } catch (mysqli_sql_exception $e) {
            $erro_msg = "Não é possível excluir este ambiente pois existem chamados vinculados a ele.";
        }
    }
}


$blocos = [];
$res_blocos = $conn->query("SELECT id_bloco, nome FROM blocos ORDER BY nome ASC");
while($b = $res_blocos->fetch_assoc()) { $blocos[] = $b; }


$sql_ambientes = "
    SELECT a.*, b.nome as nome_bloco 
    FROM ambientes a 
    JOIN blocos b ON a.id_bloco = b.id_bloco 
    ORDER BY a.nome ASC
";
$res_ambientes = $conn->query($sql_ambientes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM | Ambientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .btn-acao { border-radius: 4px; font-weight: 500; font-size: 0.85rem; padding: 4px 10px; }
    </style>
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">SGM | Gestor</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
          <form class="d-flex align-items-center" role="search">
            <span class="navbar-text me-3 text-white">
                Olá, Gestor |
            </span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Sair</a>
          </form>
        </div>
      </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-center mb-3">
            <h3 class="fw-bold text-secondary">GERENCIAR AMBIENTES</h3>
        </div>

        <?php if($erro_msg): ?>
            <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $erro_msg; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill me-2"></i>Operação realizada com sucesso!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <div class="d-flex justify-content-center gap-2 mb-4">
            <a href="<?php echo $pagina_atual; ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Atualizar Tabela</a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdicionar"><i class="bi bi-plus-circle me-1"></i>Adicionar Ambiente</button>
        </div>

        <div class="shadow-sm rounded border overflow-hidden bg-white">
            <table class="table table-hover m-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="10%" class="ps-3">ID</th>
                        <th width="35%">Nome do Ambiente</th>
                        <th width="35%">Bloco Pertencente</th>
                        <th width="20%" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($res_ambientes->num_rows == 0): ?>
                        <tr><td colspan="4" class="text-center py-4">Nenhum ambiente cadastrado.</td></tr>
                    <?php endif; ?>

                    <?php while($row = $res_ambientes->fetch_assoc()): ?>
                    <tr>
                        <td class="ps-3"><strong>#<?php echo $row['id_ambiente']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row['nome_bloco']); ?></span></td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-primary btn-acao" data-bs-toggle="modal" data-bs-target="#modalEditar<?php echo $row['id_ambiente']; ?>">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>
                                
                                <form method="POST" action="<?php echo $pagina_atual; ?>" onsubmit="return confirm('Deseja realmente apagar o ambiente <?php echo htmlspecialchars($row['nome']); ?>?');" style="margin: 0;">
                                    <input type="hidden" name="id_ambiente_del" value="<?php echo $row['id_ambiente']; ?>">
                                    <button type="submit" name="deletar_ambiente" class="btn btn-danger btn-acao">
                                        <i class="bi bi-trash3"></i> Excluir
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEditar<?php echo $row['id_ambiente']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="<?php echo $pagina_atual; ?>">
                                    <input type="hidden" name="id_ambiente" value="<?php echo $row['id_ambiente']; ?>">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fw-bold">Editar Ambiente</h5>
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
                                                <option value="">Selecione um bloco...</option>
                                                <?php foreach($blocos as $b): ?>
                                                    <option value="<?php echo $b['id_bloco']; ?>" <?php echo ($row['id_bloco'] == $b['id_bloco']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($b['nome']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" name="editar_ambiente" class="btn btn-primary">Salvar Alterações</button>
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

    <div class="container mt-4 mb-5">
        <div class="d-flex justify-content-center">
            <a href="dashboard.php" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left-circle me-2"></i>Voltar à Página Inicial</a>
        </div>
    </div>

    <div class="modal fade" id="modalAdicionar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="<?php echo $pagina_atual; ?>">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Novo Ambiente</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome do Ambiente</label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Sala de Reuniões" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Selecione o Bloco</label>
                            <select name="id_bloco" class="form-select" required>
                                <option value="">Escolha um bloco...</option>
                                <?php foreach($blocos as $b): ?>
                                    <option value="<?php echo $b['id_bloco']; ?>">
                                        <?php echo htmlspecialchars($b['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="adicionar_ambiente" class="btn btn-success">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>