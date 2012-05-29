<?php
    require_once('../init.php');
	
	//$morada=$_REQUEST['morada'] or die(json_encode('falta a morada'));
	$nome=$_REQUEST['nome'] or die(json_encode('falta a nome'));
	$numero=$_REQUEST['numero'];
	$password=$_REQUEST['password'] or die(json_encode('falta a password'));
	$username=$_REQUEST['username'] or die(json_encode('falta a username'));
	$email=$_REQUEST['email'] or die(json_encode('falta a email'));
	if(isset($_SESSION['token'])){
		$token=$_SESSION['token'];
		
	}
	$password=md5($password);
	$db->beginTransaction();
	$st=$db->prepare("INSERT INTO utilizador (nome,numero,password,username,email,google_token) VALUES (:nome,:numero,:password,:username,:email,:token)");
	//$st->bindParam(':morada',$morada);
	$st->bindParam(':nome',$nome);
	$st->bindParam(':numero',$numero);
	$st->bindParam(':password',$password);
	$st->bindParam(':username',$username);
	$st->bindParam(':email',$email);
	$st->bindParam(':token',$token);
	
	//var_dump($st);
	if(isset($_SESSION['token'])){
		/*
			$ch= curl_init ("http://web.fe.up.pt/~ei09063/lbaw/API/utilizador/update_google_calendar_data.php" );
			curl_exec($ch);
			curl_close($ch);
		*/
	}
	if($st->execute()){
		echo json_encode("ok");
	}else{
		echo json_encode($db->errorInfo());
	}
	$db->commit();
?>