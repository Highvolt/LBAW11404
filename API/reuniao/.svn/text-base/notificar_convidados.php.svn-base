<?php
require_once ('../init.php');

$idreuniao = $_REQUEST['idreuniao'] or die(json_encode('Falta id da reuniao'));
if ($_SESSION['utilizadorid'] != NULL) {
	$st = $db -> prepare("Select count(*) from reuniao where reuniaoid=:id and (criador=:c or grupodeutilizadoresid in (Select grupodeutilizadoresid from joingrupodeutilizadorestoutilizador where utilizadorid=:c and tipo=1));");
	$st -> bindParam(':id', $idreuniao);
	$st -> bindParam(':c', $_SESSION['utilizadorid']);
	$result = $st -> execute();

	if ($result) {
		$result = $st -> fetch();
		if ($result[0] > 0) {
			$st = $db -> prepare("Select reuniaoid,email,reuniao.nome as nomereu,utilizador.nome as nomeuser from conviteparareuniao,utilizador,reuniao where para=utilizador.utilizadorid and aprovado=true and reuniao.reuniaoid=conviteparareuniao.reuniaoid and reuniao.reuniaoid=:id ;");
			$st -> bindParam(':id', $idreuniao);

			$result = $st -> execute();
			if ($result) {
				$result = $st -> fetchAll();
				$url = $_SERVER['SERVER_NAME'];
					$page = $_SERVER['PHP_SELF'];

					
					$path_only = implode("/", (explode('/', $url . $page, -3)));

					$resulturl = "http://" . $path_only . '?reuniao=' . $idreuniao;
					$headers = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				foreach ($result as $key => $value) {
					
					if (mail($value['email'], '[SAR] Notificação de reunião', "<html><body><p>Olá $value[nomeuser],</p><p>Foi convidado para participar na $value[nomereu].</p><a href=$resulturl>Link</a></body></html>", $headers)) {
						//$resulturl = "ok";
					} else {
						//$resulturl = "Fail to send the email";
					}
				}
				echo json_encode("mails enviados");
			} else {
				die(json_encode($db -> errorInfo()));
			}
		} else {
			die(json_encode('Não é membro administrativo da reuniao ou grupo detentor da reunião'));
		}
	} else {
		die(json_encode($db -> errorInfo()));
	}

} else {
	die(json_encode('nao esta logado'));
}
?>