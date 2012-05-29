<?php

require_once('../init.php');

$password = $_REQUEST['password'] or die(json_encode('Falta password'));
$username = $_REQUEST['username'] or die(json_encode('Falta username'));
$password = md5($password);

$db->beginTransaction();
$st = $db->prepare('select utilizadorid, nome, numero, email from utilizador where (password=:password) AND (username=:username)');

if (!$st) {
	echo 'prepare error';
	exit;
}

$st->bindParam(':password',$password);
$st->bindParam(':username',$username);

// echo('USERNAME: '.$username.'<p> PASSWORD: '.$password.'<p>');

$st->execute();


$result = $st->fetch();
// var_dump($result);
echo('Id: '.$result['utilizadorid'].'<p>');
echo('Nome: '.$result['nome'].'<p>');
echo('Numero: '.$result['numero'].'<p>');
echo('Email: '.$result['email'].'<p>');


?>