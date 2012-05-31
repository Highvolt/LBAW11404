<?php
require_once ('../init.php');


if ($_SESSION['utilizadorid'] != NULL) {
	$st = $db -> prepare("Select reuniao.reuniaoid, nome, estado, criador, grupodeutilizadoresid, datafinal from conviteparareuniao,reuniao where (reuniao.reuniaoid=conviteparareuniao.reuniaoid and para=:c and aprovado=true) or reuniao.criador=:c;");
		$st -> bindParam(':c', $_SESSION['utilizadorid']);
	$result = $st -> execute();
	
	if ($result) {
		echo json_encode($st -> fetchAll(PDO::FETCH_ASSOC));
		
	} else {
		die(json_encode($db -> errorInfo()));
	}

} else {
	die(json_encode('nao esta logado'));
}
?>