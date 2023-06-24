<?php

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmail($email, $subject, $message) {
    // Instancia o objeto PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configura as informações do servidor de e-mail
        $mail->isSMTP();
        $mail->Host = 'localhost'; // Insira o endereço do servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'seu_email'; // Insira seu endereço de e-mail
        $mail->Password = 'sua_senha'; // Insira sua senha de e-mail
        $mail->SMTPSecure = 'tls'; // Se necessário, defina o protocolo de segurança adequado
        $mail->Port = 587; // Insira a porta do servidor SMTP

        // Configura o remetente e o destinatário
        $mail->setFrom('lucasnogueirarn37@gmail.com', 'Seu Nome'); // Insira seu endereço de e-mail e seu nome
        $mail->addAddress($email); // Insira o endereço de e-mail do destinatário

        // Configura o assunto e o corpo do e-mail
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Envia o e-mail
        $mail->send();
        echo "E-mail enviado com sucesso.";
    } catch (Exception $e) {
        echo 'Erro ao enviar o e-mail: ' . $mail->ErrorInfo;
    }
}

?>
