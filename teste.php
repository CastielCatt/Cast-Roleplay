<?php
session_start(); // Inicializa a sessão

// Inicializa as variáveis de usuário e senha
$con = mysqli_connect('localhost', 'root', '', 'samp');

$user = "";
$password = "";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $user = $_POST["username"];
    $password = $_POST["password"];

    // Verifica se houve erro na conexão
    if (!$con) {
        die('Erro na conexão com o banco de dados: ' . mysqli_connect_error());
    }

    // Consulta os dados do usuário no banco de dados
    $sql = "SELECT * FROM accounts WHERE username = '$user' AND password = '$password'";
    $result = $con->query($sql);

    // Verifica se a consulta retornou algum resultado
    if ($result->num_rows > 0) {
        // Obtém os dados do usuário
        $row = $result->fetch_assoc();

        // Define as informações do usuário na sessão
        $_SESSION["id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        $_SESSION["email"] = $row["email"];

        // Redireciona para a página de informações do jogador
        header("Location: player_info.php");
        exit();
    } else {
        echo "Usuário ou senha inválidos.";
    }

    // Fecha a conexão com o banco de dados
    $con->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tela de Login</title>
</head>
<body>
    <h2>Tela de Login</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login"> <p>Esqueceu sua senha?<a href = ""> recuperar</a></p>
    </form>
</body>
</html>
