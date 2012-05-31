<?php
require_once ('../init.php');
$idreuniao = $_REQUEST['idreuniao'] or die(json_encode('Falta id da reuniao'));
$ts = $_REQUEST['ts'] or die(json_encode('Falta data da reuniao'));
if ($_SESSION['utilizadorid'] != NULL) {
	$st = $db -> prepare("Select count(*) from reuniao where reuniaoid=:id and (criador=:c or grupodeutilizadoresid in (Select grupodeutilizadoresid from joingrupodeutilizadorestoutilizador where utilizadorid=:c and tipo=1));");
	$st -> bindParam(':id', $idreuniao);
	$st -> bindParam(':c', $_SESSION['utilizadorid']);
	$result = $st -> execute();

	if ($result) {
		$result = $st -> fetch();
		if ($result[0] > 0) {
			$st = $db -> prepare("Insert into datareuniao (reuniaoid,tempo) values (:id,to_timestamp(:tempo));");
			$st -> bindParam(':id', $idreuniao);
			$st -> bindParam(':tempo', $ts);

			$result = $st -> execute();
			if ($st -> rowCount() > 0) {
				echo json_encode("Reunião adicionada correctamente");
			} else {
				die(json_encode($db -> errorInfo()));
			}
		}else{
			die(json_encode('Não é o criador da reuniao'));
		}
	} else {
		die(json_encode($db -> errorInfo()));
	}

} else {
	die(json_encode('nao esta logado'));
}
?>