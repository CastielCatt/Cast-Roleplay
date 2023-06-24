<?php
/**
 * @autor Ske Software https://facebook.com/skesoftware
 * 
 * @since 2021
 * 
 * @package API de Recarga Automática de Cartões da Thesieure
 * 
 * @license CÓDIGO LIVRE
 * 
 * @see USADO PARA INTEGRAR RECARGA DE CARTÕES PARA LOJAS DE JOGOS, LOJAS DE SERVIÇOS, SITES DE RECARGA
 */

// INCLUIR CONFIGURAÇÃO PARA PROCESSAMENTO
require_once 'config.php';
$return = '';
if (isset($_POST['submit-doithe'])) {
    // GERAR UM ID DE REQUISIÇÃO ALEATÓRIO (NÃO ALTERE)
    $request_id = rand(100000000,999999999);

    // DEFINIR O ARRAY POSTGET PARA NULO PARA EVITAR ERROS
    $POSTGET = array();

    // ID DA REQUISIÇÃO
    $POSTGET['request_id'] = $request_id;

    // CÓDIGO DO CARTÃO DIGITADO PELO USUÁRIO
    $POSTGET['code'] = $_POST['code_card'];

    // NOME DA CONTA
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($con, $username);

    // ID DO PARCEIRO (CONFIGURADO NO ARQUIVO CONFIG.PHP)
    $POSTGET['partner_id'] = $partner_id;

    // NÚMERO DE SÉRIE DO CARTÃO DIGITADO PELO USUÁRIO
    $POSTGET['serial'] = $_POST['seri'];

    // OPERADORA DO CARTÃO DIGITADO PELO USUÁRIO
    $POSTGET['telco'] = $_POST['type'];

    // COMANDO (PADRÃO: RECARGA DO CARTÃO)
    $POSTGET['command'] = $command;

    // ORDENAR O ARRAY
    ksort($POSTGET);

    // ASSINATURA PARA RECARGA DO CARTÃO
    $sign = $partner_key;

    // ADICIONAR A ASSINATURA MD5 AO ITEM
    foreach ($POSTGET as $item) {
        $sign .= $item;
    }

    // CONVERTER A ASSINATURA PARA O FORMATO MD5 (OBRIGATÓRIO)
    $mysign = md5($sign);

    // VALOR DO CARTÃO DIGITADO PELO USUÁRIO
    $POSTGET['amount'] = $_POST['amount'];

    // ASSINATURA MD5
    $POSTGET['sign'] = $mysign;

    // GERAR URL PARA ENVIAR PARA O TSR
    $data = http_build_query($POSTGET);

    // EXECUTAR A REQUISIÇÃO CURL
    $ch = curl_init();
    // ENVIAR REQUISIÇÃO PARA O TSR (NÃO ALTERE)
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $SERVER_NAME = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    curl_setopt($ch, CURLOPT_REFERER, $SERVER_NAME);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    // FECHAR A REQUISIÇÃO PARA O TSR
    curl_close($ch);

    // DECODIFICAR A RESPOSTA JSON (CLASSE PADRÃO)
    $return = json_decode($result);

    if (isset($return->status)) {
        if ($return->status == 99) {
            // SE O CARTÃO ESTIVER PENDENTE DE PROCESSAMENTO, COLOQUE O CÓDIGO AQUI
        } elseif ($return->status == 4) {
            // SE A OPERADORA ESTIVER EM MANUTENÇÃO, COLOQUE O CÓDIGO AQUI
        } else {
            // Código a ser executado por padrão aqui
        }
    } else {
        // Código a ser executado por padrão aqui
    }
}

// SE O CARTÃO ESTIVER AGUARDANDO APROVAÇÃO, O TSR LIGARÁ AUTOMATICAMENTE PARA VOCÊ. ADICIONE UM EVENTO NO ARQUIVO CALLBACK.PHP!
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recarga de Cartão SAMP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <br>
        <br>
        <br>
        <h4 class="h4 fw-bold text-muted text-center">Recarga de Cartão SAMP</h4>
        <p class="text-muted text-center">Por favor, saia do jogo antes de recarregar o cartão e verifique se o nome do personagem (IC) está correto, pois um nome incorreto pode resultar em perda do cartão.</p>
        <div class="row">
            <div class="col-sm-6 mx-auto">
                <form method="post">
                    <div class="form-group">
                        <select name="type" class="form-select" required="">
                            <option value="">Selecione o tipo de cartão</option>
                            <option value="VIETTEL">Viettel</option>
                            <option value="MOBIFONE">Mobifone</option>
                            <option value="VINAPHONE">Vinaphone</option>
                            <option value="GATE">Gate</option>
                            <option value="ZING">Zing</option>
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <select name="amount" class="form-select" required="">
                            <option value="">Selecione o valor</option>
                            <option value="100">10.000</option>
                            <option value="200">20.000</option>
                            <option value="300">30.000</option>
                            <option value="500">50.000</option>
                            <option value="1000">100.000</option>
                            <option value="2000">200.000</option>
                            <option value="3000">300.000</option>
                            <option value="5000">500.000</option>
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <input type="number" class="form-control" name="seri" placeholder="Número de Série" required="">
                    </div>
                    <br>
                    <div class="form-group">
                        <input type="number" class="form-control" name="code_card" placeholder="Código do Cartão" required="">
                    </div>
                    <br>
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="Nome IC" required />
                    </div>
                    <br>
                    <div class="form-group">
                        <center><button type="submit" name="submit-doithe" class="btn btn-outline-primary">CONFIRMAR</button></center>
                    </div>
                </form>
                <br>
            </div>
        </div>
    </div>
</body>
</html>
