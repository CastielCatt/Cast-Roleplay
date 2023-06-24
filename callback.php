<?php
/**
 * @author Ske Software https://facebook.com/skesoftware
 * 
 * @since 2021
 * 
 * @package API de Recarga Automática de Cartões da Thesieure
 * 
 * @license CÓDIGO LIVRE
 * 
 * @see USADO PARA INTEGRAR RECARGA DE CARTÕES PARA LOJAS DE JOGOS, LOJAS DE SERVIÇOS, SITES DE RECARGA
 */

if (isset($_GET['status']) && $_GET['code'] && $_GET['tenaccg'] && $_GET['serial'] && $_GET['trans_id'] && $_GET['telco'] && $_GET['callback_sign']) {

	// CHAMADA DE CONFIGURAÇÃO
	include 'config.php';

	// STATUS
	$status = $_GET['status'];
	// CÓDIGO DO CARTÃO
	$code = $_GET['code'];
	// NÚMERO DO CARTÃO
	$serial = $_GET['serial'];
	// STATUS DA MENSAGEM (FALHA - SUCESSO) DO THESIEURE
	$message = $_GET['message'];
	// VALOR ORIGINAL DO CARTÃO
	$real_money = $_GET['value'];
	// VALOR RECEBIDO
	$geted_money = $_GET['amount'];
	// OPERADORA DO CARTÃO
	$nhamang = $_GET['telco'];
	// ID DA TRANSFERÊNCIA DO THESIEURE
	$trans_id = $_GET['trans_id'];
	// VERIFICAÇÃO DA ASSINATURA MD5
	$check_sign = md5($partner_key.$code.$serial);

	// VERIFICAR SE A ASSINATURA É VÁLIDA PARA EVITAR BUGS
	if ($_GET['callback_sign'] == $check_sign) {

		// AQUI É ONDE VOCÊ PODE SALVAR OS LOGS DE RECARGA EM UM ARQUIVO PARA VERIFICAR OS RESULTADOS, SE VOCÊ ESTIVER USANDO UM SITE REAL, LEMBRE-SE DE REMOVER ESTA PARTE
		$open = "lognapthe.txt";
		file_put_contents($open,$_GET['status'].'|'.$_GET['message'].'|'.$_GET['value'].'|'.$_GET['code'].'|'.$_GET['serial'].'|'.$_GET['telco'].'|'.$_GET['tenacc'].'|'.$_GET['trans_id'].PHP_EOL, FILE_APPEND);

		if ($status == 1) {
			// SE A RECARGA FOR BEM-SUCEDIDA, COLOQUE O CÓDIGO AQUI
			UPDATE `accounts` SET `Credits` = `Credits` + $real_money WHERE `username` = '$username'
			echo "<div class='form'>
			<h3>Recarga bem-sucedida</h3>
			<br/>Clique para <a href='registration.php'>Voltar à Página Inicial</a></div>";
		} elseif ($status == 2) {
			// SE A RECARGA FOR BEM-SUCEDIDA, MAS O VALOR ESTIVER INCORRETO, COLOQUE O CÓDIGO AQUI
			UPDATE `accounts` SET `Credits` = `Credits` + $geted_money WHERE `username` = '$username'
			echo "<div class='form'>
			<h3>Recarga bem-sucedida, mas você inseriu o valor errado, então não receberá todos os créditos</h3>
			<br/>Clique para <a href='registration.php'>Voltar à Página Inicial</a></div>";
		} elseif ($status == 3) {
			// SE O CARTÃO ESTIVER INCORRETO OU INVÁLIDO, COLOQUE O CÓDIGO AQUI
			echo "<div class='form'>
			<h3>Recarga sem sucesso. Código do cartão ou número serial incorretos</h3>
			<br/>Clique para <a href='registration.php'>Voltar à Página Inicial</a></div>";
		} elseif ($status == 4) {
			// SE A OPERADORA ESTIVER EM MANUTENÇÃO, COLOQUE O CÓDIGO AQUI
			echo "<div class='form'>
			<h3>Esta operadora está em manutenção. Por favor, tente novamente mais tarde. Obrigado</h3>
			<br/>Clique para <a href='registration.php'>Voltar à Página Inicial</a></div>";
		} elseif ($status == 99) {
			// SE O CARTÃO ESTIVER EM PROCESSAMENTO, COLOQUE O CÓDIGO AQUI
			echo "<div class='form'>
			<h3>O cartão está em processamento. Aguarde de 3 a 5 minutos e, se os créditos ainda não forem adicionados à sua conta, entre em contato com o administrador</h3>
			<br/>Clique para <a href='registration.php'>Voltar à Página Inicial</a></div>";
		} else {
			// SE O STATUS DO CARTÃO NÃO FOR RECONHECIDO, COLOQUE O CÓDIGO AQUI
			echo "<div class='form'>
			<h3>Status do cartão desconhecido!</h3>
			<br/>Clique para <a href='registration.php'>Voltar à Página Inicial</a></div>";
		}

	} else {
		// ASSINATURA INVÁLIDA
	}
} else {
	die('Sem permissão para acessar esta página');
}
?>
