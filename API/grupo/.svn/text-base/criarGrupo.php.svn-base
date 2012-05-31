<?php

require_once('../init.php');

$password = $_REQUEST['password'] or die(json_encode('Falta password'));
$username = $_REQUEST['username'] or die(json_encode('Falta username'));
$nomeGrupo = $_REQUEST['nomeGrupo'] or die(json_encode('Falta nome do grupo'));

$password = md5($password);

$db->beginTransaction();
$st = $db->prepare('select utilizadorid from utilizador where (password=:password) AND (username=:username)');

if (!$st) {
	echo 'prepare error1';
	exit;
}

$st->bindParam(':password',$password);
$st->bindParam(':username',$username);

$st->execute();

$result = $st->fetch();
// var_dump($result);
$id = $result['utilizadorid'];
echo('Id: '.$result['utilizadorid'].'<p>');
echo('IDD: '.$id.'<p>');
	
$st2 = $db->prepare("INSERT INTO grupodeutilizadores (nome,criador) VALUES (:nomeGrupo,$id)");

if (!$st2) {
	echo 'prepare error2';
	exit;
}

$st2->bindParam(':nomeGrupo',$nomeGrupo);
$st2->execute();
// $result2 = $st2->fetch();

echo('NomeGrupo: '.$nomeGrupo.'<p>');
?>