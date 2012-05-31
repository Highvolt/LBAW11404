<?php
require_once ('../init.php');

$id = $_REQUEST['conviteid'] or die(json_encode('Falta o id do convite'));
$idreuniao = $_REQUEST['idreuniao'] or die(json_encode('Falta id da reuniao'));
if ($_SESSION['utilizadorid'] != NULL) {
	$st = $db -> prepare("Select count(*) from reuniao where reuniaoid=:id and (criador=:c or grupodeutilizadoresid in (Select grupodeutilizadoresid from joingrupodeutilizadorestoutilizador where utilizadorid=:c and tipo=1));");
	$st -> bindParam(':id', $idreuniao);
	$st -> bindParam(':c', $_SESSION['utilizadorid']);
	$result = $st -> execute();
	
	if ($result) {
		$result = $st -> fetch();
		if ($result[0] > 0) {
			$st = $db -> prepare("Update conviteparareuniao set aprovado=true where conviteparareuniaoid=:id ;");
			$st -> bindParam(':id', $id);
			

			$result = $st -> execute();
			if ($st -> rowCount() > 0) {
				echo json_encode("Convite aprovado correctamente");
			} else {
				die(json_encode($db -> errorInfo()));
			}
		}else{
			die(json_encode('Não é membro administrativo da reuniao ou grupo detentor da reunião'));
		}
	} else {
		die(json_encode($db -> errorInfo()));
	}

} else {
	die(json_encode('nao esta logado'));
}
?>