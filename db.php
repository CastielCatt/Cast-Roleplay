<?php
$con = mysqli_connect('localhost', 'root', '', 'samp');

if (!$con) {
    die('Erro na conexão com o banco de dados: ' . mysqli_connect_error());
}
?>