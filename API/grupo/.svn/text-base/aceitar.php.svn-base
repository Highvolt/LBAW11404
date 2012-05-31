<?php
require_once ('../init.php');
$id = $_REQUEST['id'] or die(json_encode('falta o id do grupo'));
if ($_SESSION['utilizadorid'] != NULL) {

	$st = $db -> prepare("Select count(*) from joingrupodeutilizadorestoutilizador where utilizadorid=:c and active=false and grupodeutilizadoresid=:id;");
	$st -> bindParam(':c', $_SESSION['utilizadorid']);
	$st -> bindParam(':id', $id);
	$result = $st -> execute();
	if ($result) {
		//echo json_encode($st->fetchAll(PDO::FETCH_ASSOC));
		$result=$st->fetch();
		if ($result[0] > 0) {
			$st = $db -> prepare("Update joingrupodeutilizadorestoutilizador set active=true where utilizadorid=:c and grupodeutilizadoresid=:id;");
			$st -> bindParam(':c', $_SESSION['utilizadorid']);
			$st -> bindParam(':id', $id);
			$result = $st -> execute();
			if($result){
				echo json_encode('ok');
			}else{
				die(json_encode($db -> errorInfo()));
			}
		} else {
			die(json_encode('Não tem nenhum convite para este grupo'));
		}
	} else {
		die(json_encode($db -> errorInfo()));
	}

} else {
	die(json_encode('nao esta logado'));
}
?>