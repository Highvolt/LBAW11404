<?php
require_once('../init.php');
$id = $_REQUEST['id'] or die(json_encode('falta o id do grupo'));
$id_d = $_REQUEST['id_user'] or die(json_encode('falta o id do utilizador'));
$tipo = $_REQUEST['tipo'] or die(json_encode('falta o Tipo'));
if ($_SESSION['utilizadorid'] != NULL and $id_d!=$_SESSION['utilizadorid']) {

	$st = $db -> prepare('Select tipo=1 as admin from joingrupodeutilizadorestoutilizador where active=true and bloqueado=false and utilizadorid=:c and grupodeutilizadoresid=:id and :id_d in (Select utilizadorid from joingrupodeutilizadorestoutilizador where grupodeutilizadoresid=:id and active=true and tipo!=1);');
	$st -> bindParam(':c', $_SESSION['utilizadorid']);
	$st -> bindParam(':id', $id);
	$st -> bindParam(':id_d', $id_d);
	$result = $st -> execute();
	if ($result) {
		$result = $st -> fetch();
		if ($result[0] == TRUE) {
			$st = $db -> prepare('Update joingrupodeutilizadorestoutilizador set tipo=:t where grupodeutilizadoresid=:id and :id_d=utilizadorid;');
			$st -> bindParam(':id', $id);
			$st -> bindParam(':id_d', $id_d);
			$st -> bindParam(':t', $tipo);
			$result = $st -> execute();
			if ($st->rowCount()>0) {
				echo json_encode("Utilizador com id $id_d no grupo com id $id tem agora permissões de $tipo.");

			} else {
				die(json_encode($db -> errorInfo()));
			}

		}else{
			die(json_encode("Pedido inválido"));
		}

	} else {
		die(json_encode($db -> errorInfo()));
	}

} else {
	die(json_encode('nao esta logado'));
}
?>