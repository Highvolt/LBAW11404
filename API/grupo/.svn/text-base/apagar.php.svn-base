<?php
require_once ('../init.php');
$id = $_REQUEST['id'] or die(json_encode('falta o id do grupo'));
if ($_SESSION['utilizadorid'] != NULL) {

	$st = $db -> prepare('Select count(*) from joingrupodeutilizadorestoutilizador where utilizadorid=:idu and grupodeutilizadoresid=:idg and tipo=1 and active=true and bloqueado=false and exclude=false;');
	$st -> bindParam(':idg', $id);
	$st -> bindParam(':idu', $_SESSION['utilizadorid']);
	$result = $st -> execute();
	if ($result) {
		$result = $st -> fetch();
		//var_dump($result);
		if ($result[0] <= 0 || $id==1) {
			die(json_encode('Você não é administrador deste grupo'));
		} else {
			$st = $db -> prepare('Delete from grupodeutilizadores where grupodeutilizadoresid=:idg;');
			$st -> bindParam(':idg', $id);
			$result = $st -> execute();
			if ($result) {
				die(json_encode("Grupo com id $id foi eliminado."));
			} else {
				die(json_encode($db -> errorInfo()));
			}

		}
	} else {
		die(json_encode($db -> errorInfo()));
	}

} else {
	die(json_encode('nao esta logado'));
}
?>