<?php 
    require_once('../init.php');
	
	
	$id=$_REQUEST['id'] or die(json_encode('falta o id do grupo'));
	if($_SESSION['utilizadorid']!=NULL){
		
		$st=$db->prepare('Select grupodeutilizadores.nome as nome_grupo,grupodeutilizadores.grupodeutilizadoresid,utilizador.username,utilizador.nome,tipo from utilizador,joingrupodeutilizadorestoutilizador,grupodeutilizadores where bloqueado=false and joingrupodeutilizadorestoutilizador.utilizadorid=utilizador.utilizadorid and grupodeutilizadores.grupodeutilizadoresid=joingrupodeutilizadorestoutilizador.grupodeutilizadoresid and grupodeutilizadores.grupodeutilizadoresid=:c and active=true and bloqueado=false and exclude=false order by tipo;');
		$st->bindParam(':c',$id);
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