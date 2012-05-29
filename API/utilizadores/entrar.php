<?php
require_once ('../init.php');

$password = $_REQUEST['password'] or die(json_encode('falta a password'));
$username = $_REQUEST['username'] or die(json_encode('falta a username'));
//if(!isset($_REQUEST['md5'])){
$password = md5($password);
//}
$db -> beginTransaction();
$st = $db -> prepare("Select utilizadorid,nome, username,email,google_token from utilizador where password=:password and username=:username");

$st -> bindParam(':password', $password);
$st -> bindParam(':username', $username);

if ($st -> execute()) {
	$result = $st -> fetch(PDO::FETCH_ASSOC);
	$db -> commit();
			foreach ($result as $key => $value) {
			$_SESSION[$key] = $value;
		}
	if (is_bool($result)) {
		echo json_encode("utilizador/password invalidos");
	} else {
		
		$token = $result['google_token'];
		if ($token != NULL) {
			require_once '../../Google_API/src/apiClient.php';
			require_once '../../Google_API/src/contrib/apiOauth2Service.php';
			$_SESSION['token'] = $token;
			$_REQUEST['session'] = 1;
			//include('entrar_google.php');
			$client = new apiClient();
			$client -> setApplicationName("Google UserInfo PHP Starter Application");
			// Visit https://code.google.com/apis/console?api=plus to generate your
			// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
			$client -> setClientId('323742889259.apps.googleusercontent.com');
			$client -> setClientSecret('lbURidK2mjUFanObyO_xloBt');
			$client -> setRedirectUri('https://web.fe.up.pt/~ei09063/lbaw/API/utilizadores/entrar_google.php');
			$client -> setDeveloperKey('AIzaSyARYqssdoPRxp6xjrwHW53EUPjlzUlUjuI');
			$oauth2 = new apiOauth2Service($client);
			$client -> setAccessToken($_SESSION['token']);
			$user = $oauth2 -> userinfo -> get();

			$_SESSION['img'] = filter_var($user['picture'], FILTER_VALIDATE_URL);
			$status['silent']=1;
			/*$ch= curl_init ("http://web.fe.up.pt/~ei09063/lbaw/API/utilizador/update_google_calendar_data.php" );
			curl_exec($ch);
			curl_close($ch);*/
			
		}


		echo json_encode("ok");
	}

} else {
	echo json_encode("fail");
}

?>