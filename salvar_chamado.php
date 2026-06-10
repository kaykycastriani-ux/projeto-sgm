<?php
// salvar_chamado.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aqui você receberia os dados do formulário
    $local = $_POST['local'];
    $prioridade = $_POST['prioridade'];
    $assunto = $_POST['assunto'];
    $descricao = $_POST['descricao'];

    /* DICA DO GEMINI: 
       No futuro, aqui entrará o código para salvar no Banco de Dados (INSERT INTO...)
       Por enquanto, vamos apenas simular que funcionou para sua apresentação não travar.
    */

    echo "<script>
            alert('Chamado enviado com sucesso para o $local!');
            window.location.href = 'dashboard.php';
          </script>";
} else {
    // Se alguém tentar acessar esse arquivo direto, volta pro dashboard
    header("Location: dashboard.php");
}
?>