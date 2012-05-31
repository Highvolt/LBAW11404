<?php
     require_once('../init.php');
	 
	 if($_SESSION['utilizadorid']!=NULL){
		
		$st=$db->prepare("Select grupodeutilizadores.grupodeutilizadoresid,nome,criador, active, tipo, criador=:c as criado_por_mim, bloqueado  from grupodeutilizadores,joingrupodeutilizadorestoutilizador where utilizadorid=:c and grupodeutilizadores.grupodeutilizadoresid=joingrupodeutilizadorestoutilizador.grupodeutilizadoresid;");
		$st->bindParam(':c',$_SESSION['utilizadorid']);
		$result=$st->execute();
		if($result){
			echo json_encode($st->fetchAll(PDO::FETCH_ASSOC));
		}else{
			die(json_encode($db->errorInfo()));
		}
		
		
	}else{
		die(json_encode('nao esta logado'));
	}
	 
?>