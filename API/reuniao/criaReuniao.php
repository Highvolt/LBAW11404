<?php
     require_once('../init.php');
	 $idgrupo=$_REQUEST['id_grupo'] or $idgrupo=0;
	 $periodicidade=$_REQUEST['periodicidade'] or $periodicidade=0;
	 $nome=$_REQUEST['nome'] or die(json_encode('Falta o nome da reunião'));
	 $duracao=$_REQUEST['duracao'] or $duracao=60;
	 $local=$_REQUEST['local'] or $local="";
	 $descricao=$_REQUEST['descricao'] or $decricao=0;
	 if($_SESSION['utilizadorid']!=NULL){
		
		$st=$db->prepare("Insert into reuniao (grupodeutilizadoresid,nome,local,descricao,publica,periodicidade,duracao,criador) values (:g,:n,:l,:d,:p,:per,:d,:c);");
		$st->bindParam(':g',$idgrupo);
		$st->bindParam(':n',$nome);
		$st->bindParam(':l',$local);
		$st->bindParam(':d',$descricao);
		$st->bindParam(':per',$periodicidade);
		$st->bindParam(':p',$idgrupo!=0);
		$st->bindParam(':d',$duracao);
		$st->bindParam(':c',$_SESSION['utilizadorid']);
		$result=$st->execute();
		if($st->rowCount()>0){
			echo json_encode("Reunião adicionada correctamente");
		}else{
			die(json_encode($db->errorInfo()));
		}
		
		
	}else{
		die(json_encode('nao esta logado'));
	}
	 
?>