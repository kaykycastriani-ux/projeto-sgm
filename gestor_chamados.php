Você disse
<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SGM - Gestão de Chamados</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

   

    <style>

        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }

        .navbar { background-color: #1a1d21 !important; border-bottom: 3px solid #0d6efd; padding: 1rem 0; }

        .nav-link { color: #adb5bd !important; font-weight: 500; transition: 0.3s; border-radius: 8px; margin: 0 4px; }

        .nav-link:hover, .nav-link.active { color: #fff !important; background: rgba(255,255,255,0.1); }

        .card { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

        .btn-filter { border-radius: 50px; padding: 8px 20px; font-weight: 600; border-width: 2px; }

        .status-badge { padding: 8px 14px; border-radius: 10px; font-weight: 700; font-size: 0.7rem; text-transform: uppercase; }

        .priority-dot { height: 10px; width: 10px; border-radius: 50%; display: inline-block; margin-right: 6px; }

    </style>

</head>

<body>



    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">

        <div class="container">

            <a class="navbar-brand fw-bold" href="gestor_dashboard.php">

                <i class="bi bi-shield-fill-check text-primary me-2"></i>SGM ADMIN

            </a>

            <div class="navbar-nav ms-auto align-items-center">

                <a class="nav-link" href="gestor_dashboard.php"><i class="bi bi-house-door-fill me-1"></i> Voltar</a>

                <a class="nav-link active" href="gestor_chamados.php"><i class="bi bi-ticket-perforated-fill me-1"></i> Chamados</a>

                <a class="nav-link text-danger ms-3" href="api/logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>

            </div>

        </div>

    </nav>



    <div class="container mt-4 pb-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="fw-bold m-0 text-dark text-uppercase" style="letter-spacing: -1px;">Controle de Chamados</h2>

        </div>



        <div class="mb-4 d-flex gap-2 flex-wrap">

            <button class="btn btn-filter btn-outline-secondary" onclick="carregarChamados('')"><i class="bi bi-grid-1x2-fill me-2"></i>Todos</button>

            <button class="btn btn-filter btn-outline-primary" onclick="carregarChamados('aberto')"><i class="bi bi-envelope-fill me-2"></i>Abertos</button>

            <button class="btn btn-filter btn-outline-warning text-dark" onclick="carregarChamados('em_execucao')"><i class="bi bi-gear-wide-connected me-2"></i>Em Execução</button>

            <button class="btn btn-filter btn-outline-success" onclick="carregarChamados('concluido')"><i class="bi bi-check-circle-fill me-2"></i>Concluídos</button>

        </div>



        <div class="card overflow-hidden">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr class="text-secondary small">

                            <th class="ps-4">ID</th>

                            <th>SOLICITANTE</th>

                            <th>LOCAL / BLOCO</th>

                            <th>PRIORIDADE</th>

                            <th>TÉCNICO</th>

                            <th>STATUS</th>

                            <th class="text-center">AÇÕES</th>

                        </tr>

                    </thead>

                    <tbody id="tabelaGeral">

                        </tbody>

                </table>

            </div>

        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        const confPrioridade = {

            'urgente': 'bg-danger',

            'alta': 'bg-warning',

            'media': 'bg-primary',

            'baixa': 'bg-secondary'

        };

       

        const confStatus = {

            'aberto': 'bg-secondary text-white',

            'em_execucao': 'bg-warning text-dark',

            'concluido': 'bg-success text-white',

            'fechado': 'bg-dark text-white'

        };



        async function carregarChamados(status = '') {

            const tabela = document.getElementById('tabelaGeral');

            tabela.innerHTML = `<tr><td colspan="7" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></td></tr>`;

           

            try {

                const response = await fetch(`api/gestor_chamados.php?status=${status}`);

                const chamados = await response.json();



                if (chamados.length === 0) {

                    tabela.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-muted">Nenhum chamado registrado com este status.</td></tr>`;

                    return;

                }



                tabela.innerHTML = chamados.map(c => `

                    <tr>

                        <td class="ps-4 text-muted">#${c.id_chamado}</td>

                        <td><div class="fw-bold">${c.solicitante_nome}</div></td>

                        <td>

                            <div class="fw-semibold text-dark">${c.ambiente_nome}</div>

                            <div class="small text-muted">${c.bloco_nome}</div>

                        </td>

                        <td>

                            <span class="priority-dot ${confPrioridade[c.prioridade]}"></span>

                            <span class="small fw-bold text-uppercase">${c.prioridade}</span>

                        </td>

                        <td>

                            <div class="d-flex align-items-center gap-2">

                                <i class="bi bi-person-badge text-muted"></i>

                                <span>${c.tecnico_nome || '<em class="text-muted">Não atribuído</em>'}</span>

                            </div>

                        </td>

                        <td><span class="status-badge ${confStatus[c.status]}">${c.status.replace('_', ' ')}</span></td>

                        <td class="text-center">

                            <a href="gestor_detalhes.php?id=${c.id_chamado}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm">

                                <i class="bi bi-pencil-square me-1"></i> GERENCIAR

                            </a>

                        </td>

                    </tr>

                `).join('');

            } catch (error) {

                tabela.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-5"><i class="bi bi-exclamation-triangle me-2"></i>Erro ao carregar dados da API.</td></tr>`;

            }

        }



        // Início automático

        carregarChamados();

    </script>

</body>

</html>