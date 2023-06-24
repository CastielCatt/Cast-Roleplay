<?php
session_start(); // Inicializa a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION["id"])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

// Obtém as informações do usuário armazenadas nas variáveis de sessão
$id = $_SESSION["id"];
$username = $_SESSION["username"];
$email = $_SESSION["email"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Informações do Jogador</title>
</head>
<body>
    <h2>Informações do Jogador</h2>
    <p>ID: <?php echo $id; ?></p>
    <p>Nome de Usuário: <?php echo $username; ?></p>
    <p>Email: <?php echo $email; ?></p>
</body>
</html>
