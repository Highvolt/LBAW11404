<?php
require_once ('../init.php');
$idreuniao = $_REQUEST['idreuniao'] or die(json_encode('Falta id da reuniao'));
$id = $_REQUEST['user'] or die(json_encode('Falta utilizador a convidar'));
$convocado=isset($_REQUEST['convocado']);
if ($_SESSION['utilizadorid'] != NULL) {
	$st = $db -> prepare("Select count(*) from reuniao where reuniaoid=:id and (criador=:c or :c in (Select para from conviteparareuniao where aprovado=true and reuniaoid=:id) or grupodeutilizadoresid in (Select grupodeutilizadoresid from joingrupodeutilizadorestoutilizador where utilizadorid=:c));");
	$st -> bindParam(':id', $idreuniao);
	$st -> bindParam(':c', $_SESSION['utilizadorid']);
	$result = $st -> execute();
	
	if ($result) {
		$result = $st -> fetch();
		if ($result[0] > 0) {
			$st = $db -> prepare("Insert into conviteparareuniao (de,para,reuniaoid,convidado,convocado) values (:de,:para,:rid,:inv,:conv);");
			$st -> bindParam(':rid', $idreuniao);
			$st -> bindParam(':de',  $_SESSION['utilizadorid']);
			$st -> bindParam(':para',  $id);
			$st -> bindParam(':inv',  !$convocado);
			$st -> bindParam(':conv',  $convocado);

			$result = $st -> execute();
			if ($st -> rowCount() > 0) {
				echo json_encode("Convite enviado correctamente");
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