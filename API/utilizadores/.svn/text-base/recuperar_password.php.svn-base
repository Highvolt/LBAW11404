<?php
require_once ('../init.php');

$username = $_REQUEST['username'] or die(json_encode('falta a username'));
//if(!isset($_REQUEST['md5'])){
//$password = md5($password);
//}
$db -> beginTransaction();
$st = $db -> prepare("Select utilizadorid,nome, username,email,password from utilizador where username=:usernames");


$st -> bindParam(':usernames', $username);

if ($st -> execute()) {
	$result = $st -> fetch(PDO::FETCH_ASSOC);
	$db -> commit();
			/*foreach ($result as $key => $value) {
			$_SESSION[$key] = $value;
		}*/
		if(!isset($_REQUEST['code'])&&!isset($_REQUEST['ts'])){
			$tm=time();
			$url = $_SERVER['SERVER_NAME']; 
			$page = $_SERVER['PHP_SELF']; 

			$recoverykey=$tm.'-'.$result['password'].'-'.md5($tm).'-'.$result['username'];
			$path_only = implode("/", (explode('/', $url.$page, -3)));
			
			$resulturl="http://".$path_only.'?code='.md5($recoverykey).'&ts='.$tm.'&username='.$result['username'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			if(mail($result['email'], '[SAR] Recuperação da palavra-passe', "<html><body><a href=$resulturl>Link</a></body></html>",$headers)){
				$resulturl="ok";
			}else{
				$resulturl="Fail to send the email";
			}
		}else{
			$tm=$_REQUEST['ts'];
			$recoverykey=md5($tm.'-'.$result['password'].'-'.md5($tm).'-'.$result['username']);
			//echo "Recovery: ".$recoverykey." Code: ".$_REQUEST['code'];
			$tc=time();
			if($recoverykey==$_REQUEST['code']){
					if($tc-$tm<1800){
						$resulturl="ok";
					}else{
						$resulturl="too old";
					}
			}
		}
		echo json_encode($resulturl);
	}
 else {
	echo json_encode($st->errorInfo());
}

?>