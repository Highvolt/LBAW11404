<?php
require_once('../init.php');
$id = $_REQUEST['id'] or die(json_encode('falta o id do grupo'));
$id_d = $_REQUEST['id_user'] or die(json_encode('falta o id do utilizador a bloquear'));

if ($_SESSION['utilizadorid'] != NULL and $id_d!=$_SESSION['utilizadorid']) {

	$st = $db -> prepare('Select tipo=1 as admin from joingrupodeutilizadorestoutilizador where active=true and bloqueado=false and utilizadorid=:c and grupodeutilizadoresid=:id and :id_d not in (Select utilizadorid from joingrupodeutilizadorestoutilizador where grupodeutilizadoresid=:id);');
	$st -> bindParam(':c', $_SESSION['utilizadorid']);
	$st -> bindParam(':id', $id);
	$st -> bindParam(':id_d', $id_d);
	$result = $st -> execute();
	if ($result) {
		$result = $st -> fetch();
		if ($result[0] == TRUE) {
			$st = $db -> prepare('Insert into joingrupodeutilizadorestoutilizador (utilizadorid,grupodeutilizadoresid,tipo) values (:uid,:gid,:tip);');
			$st -> bindParam(':gid', $id);
			$st -> bindParam(':uid', $id_d);
			if(isset($_REQUEST['tipo'])){
				$st -> bindParam(':tip', $_REQUEST['tipo']);
			}else{
				$tipo=3;
				$st -> bindParam(':tip', $tipo);
			}
			$result = $st -> execute();
			if ($result) {
				echo json_encode("Utilizador com id $id_d convidado para o grupo com id $id.");

			} else {
				die(json_encode($db -> errorInfo()));
			}

		}else{
			die(json_encode("Pedido invalido"));
		}

	} else {
		die(json_encode($db -> errorInfo()));
	}

} else {
	die(json_encode('nao esta logado'));
}
?>