<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SGM Admin</title>
    <style>
           body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .left {
            font-weight: bold;
            font-size: 18px;
        }
        header nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 600;
        }
        header nav a:hover {
            text-decoration: underline;
        }
        main {
            padding: 20px 150px;
        }
        h1 {
            margin-bottom: 10px;
        }
        .filter-options {
            margin-bottom: 15px;
            font-weight: 600;
        }
        .filter-options span {
            margin-right: 20px;
            cursor: pointer;
            color: #000000ff;
        }
        .filter-options span:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-fechado {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
            padding: 3px 7px;
            border-radius: 3px;
            font-size: 0.9em;
        }
        .btn-nova {
            background-color: #2e7d32;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            float: right;
            margin-bottom: 10px;
        }
        .btn-nova:hover {
            background-color: #1b4d1b;
        }
     
    </style>
</head>
<body>
    <header>
        <div class="left">SGM Admin</div>
        <nav>
            <a href="#">Chamados</a>
            <a href="#">Locais</a>
         <a href="api/logout.php" class="btn btn-outline-danger"><i class="bi bi-box-arrow-right"></i>Sair</a>
        </nav>
    </header>

    <main>
        <h1>Todos os chamados</h1>

        <div class="filter-options">
            <span>Todos</span>
            <span>Abertos</span>
            <span>Em Andamento</span>
            <span>Concluídos</span>
        </div>

        <button class="btn-nova">+Nova solicitação</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Local</th>
                    <th>Descrição</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#1</td>
                    <td>Bloco Admin</td>
                    <td>Vazamento</td>
                    <td><span class="status-fechado">FECHADO</span></td>
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>
