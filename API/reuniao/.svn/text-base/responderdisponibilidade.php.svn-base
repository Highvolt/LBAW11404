<?php
require_once ('../init.php');

$id = $_REQUEST['id'] or die(json_encode('Falta id data'));
$disp = $_REQUEST['id'] or die(json_encode('falta o grau'));
if ($_SESSION['utilizadorid'] != NULL) {
	$st = $db -> prepare("Select count(*) from disponibilidade where datareuniaoid=:d and reuniaoid in (Select reuniao.reuniaoid from conviteparareuniao,reuniao where ((reuniao.reuniaoid=conviteparareuniao.reuniaoid and para=:c and aprovado=true) or reuniao.criador=:c)) and estado!=9;");
	$st -> bindParam(':c', $_SESSION['utilizadorid']);
	$st -> bindParam(':d', $id);
	$result = $st -> execute();

	if ($result) {
		$result = $st -> fetch();
		if ($result[0] > 0) {
			$st = $db -> prepare("Update disponibilidade set graus=:v where datareuniaoid=:id and utilizadorid=:c;");
			$st -> bindParam(':id', $id);
			$st -> bindParam(':c', $_SESSION['utilizadorid']);
			$st -> bindParam(':v', $disp);
			$result = $st -> execute();

			if ($st -> rowCount() > 0) {
				echo json_encode('valor de disponibilidade grau actualizado');
			} else {
				$st = $db -> prepare("Insert into disponibilidade (graus,datareuniaoid,utilizadorid) values (:v,:id,:c);");
				$st -> bindParam(':id', $id);
				$st -> bindParam(':c', $_SESSION['utilizadorid']);
				$st -> bindParam(':v', $disp);
				$result = $st -> execute();

				if ($st -> rowCount() > 0) {
					echo json_encode('valor de disponibilidade grau respondido');
				}
				
			}
		} else {
			die(json_encode('Pedido inválido'));
		}
	} else {
		die(json_encode($db -> errorInfo()));
	}

} else {
	die(json_encode('nao esta logado'));
}
?>