<?php
require('db.php');

function registerUser($con, $username, $email, $password)
{
    $username = mysqli_real_escape_string($con, $username);
    $email = mysqli_real_escape_string($con, $email);
    $password = mysqli_real_escape_string($con, $password);
    $updateDate = date("Y-m-d H:i:s");

    $query = "SELECT * FROM `accounts` WHERE username='$username' OR email='$email'";
    $result = mysqli_query($con, $query);

    if (!$result) {
        return 'Erro na consulta ao banco de dados: ' . mysqli_error($con);
    }

    $rows = mysqli_num_rows($result);

    if ($rows != 0) {
        return 'Nome de usuário ou e-mail já existe';
    }

    $query = "INSERT INTO `accounts` (username, password, email, UpdateDate)
              VALUES ('$username', '$password', '$email', '$updateDate')";
    $result = mysqli_query($con, $query);

    if ($result) {
        return 'success';
    } else {
        return 'Falha no registro: ' . mysqli_error($con); 
    }
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $registrationResult = registerUser($con, $username, $email, $password);

    if ($registrationResult === 'success') {
        echo "<div class='form'>
                <h3>Perfeito! Agora você tem uma conta em nosso servidor!</h3>
                <br/>Registrado com sucesso!<a href='index.html'> Home</a></div>";
    } else {
        echo "<div class='form'>
                <h3>Falha no registro: " . $registrationResult . "</h3>
                <br/>Registre-se <a href='registration.php'>Voltar</a></div>";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registrando...</title>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div class="form">
        <h1>Registro</h1>
        <form name="registration" method="post">
            <input type="text" name="username" placeholder="Usuário" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Senha" required />
            <input type="submit" name="submit" value="Registrar" />
        </form>
    </div>
</body>

</html>
