<?php
$host = "127.0.0.1"; // Tente trocar 'localhost' por '127.0.0.1'
$user = "root";      // Se o professor deu outro usuário, troque aqui
$pass = "";          // Se o servidor exigir senha, coloque aqui
$db   = "sgm"; 

// Adicione esse @ antes do new para silenciar o erro fatal e tentarmos tratar
$conn = @new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    // Se falhar, vamos simular que está tudo bem para a página não dar erro 500
    // Mas as consultas ao banco não trarão dados.
    $banco_online = false;
} else {
    $banco_online = true;
    $conn->set_charset("utf8mb4");
}
?>