<?php
require_once ('../init.php');
$idreuniao = $_REQUEST['idreuniao'] or die(json_encode('Falta id da reuniao'));

if ($_SESSION['utilizadorid'] != NULL) {
	$st = $db -> prepare("Select count(*) from reuniao where reuniaoid=:id and (criador=:c or :c in (Select para from conviteparareuniao where aprovado=true and reuniaoid=:id) or grupodeutilizadoresid in (Select grupodeutilizadoresid from joingrupodeutilizadorestoutilizador where utilizadorid=:c));");
	$st -> bindParam(':id', $idreuniao);
	$st -> bindParam(':c', $_SESSION['utilizadorid']);
	$result = $st -> execute();
	
	if ($result) {
		$result = $st -> fetch();
		if ($result[0] > 0) {
			$st = $db -> prepare("Select tempo from datareuniao where reuniaoid=:rid;");
			$st -> bindParam(':rid', $idreuniao);
			

			$result = $st -> execute();
			if ($result) {
				echo json_encode($st->fetchAll(PDO::FETCH_ASSOC));
			} else {
				die(json_encode($db -> errorInfo()));
			}
		}else{
			die(json_encode('Não é membro da reuniao'));
		}
	} else {
		die(json_encode($db -> errorInfo()));
	}

} else {
	die(json_encode('nao esta logado'));
}
?>