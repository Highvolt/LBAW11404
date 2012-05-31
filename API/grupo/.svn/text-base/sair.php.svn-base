<?php
     require_once('../init.php');
	 $id = $_REQUEST['id'] or die(json_encode('falta o id do grupo'));
	 if($_SESSION['utilizadorid']!=NULL){
		
		$st=$db->prepare("Select count(*) from joingrupodeutilizadorestoutilizador where grupodeutilizadoresid=:id and utilizadorid=:c");
		$st->bindParam(':c',$_SESSION['utilizadorid']);
		$st->bindParam(':id',$id);
		$result=$st->execute();
		if($result){
			$result=$st->fetch();
			if($result[0]>0 and $id!=1){
				unset($st);
				$db -> beginTransaction();
				$st=$db->prepare("Delete from joingrupodeutilizadorestoutilizador where grupodeutilizadoresid=:id and utilizadorid=:c");
				$st->bindParam(':c',$_SESSION['utilizadorid']);
				$st->bindParam(':id',$id);
				$result=$st->execute();
				$db -> commit();
				if($st->rowCount()>0){
					echo json_encode('ok');
				}else{
			die(json_encode($db->errorInfo()));
		}
			}else{
				die(json_encode('Pedido inválido!'));
			}
		}else{
			die(json_encode($db->errorInfo()));
		}
		
		
	}else{
		die(json_encode('nao esta logado'));
	}
	 
?>