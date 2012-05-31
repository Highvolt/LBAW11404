<?php 
    require_once('../init.php');
	
	
	$nome=$_REQUEST['nome'] or die(json_encode('falta a nome'));
	if($_SESSION['utilizadorid']!=NULL){
		
		$st=$db->prepare("Select count(*) from grupodeutilizadores where criador=:c;");
		$st->bindParam(':c',$_SESSION['utilizadorid']);
		$result=$st->execute();
		if($result){
			$result=$st->fetch();
		}else{
			die(json_encode($db->errorInfo()));
		}
		$result[0]<=12 or die(json_encode('Ja criou demasiados grupos'));
		$db->beginTransaction();
		$st=$db->prepare("INSERT INTO grupodeutilizadores (nome,criador) VALUES (:nome,:c)");
		//$st->bindParam(':morada',$morada);
		$st->bindParam(':nome',$nome);
		$st->bindParam(':c',$_SESSION['utilizadorid']);
			if($st->execute()){
				echo json_encode("ok");
			}else{
				echo json_encode($db->errorInfo());
			}
		$db->commit();
		
		
	}else{
		die(json_encode('nao esta logado'));
	}
	

?>