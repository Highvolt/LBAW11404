<?php
require_once '../init.php';
require_once '../../Google_API/src/apiClient.php';
require_once '../../Google_API/src/contrib/apiOauth2Service.php';
require_once '../../Google_API/src/contrib/apiCalendarService.php';

/*Client ID:
 323742889259.apps.googleusercontent.com
 Email address:
 323742889259@developer.gserviceaccount.com
 Client secret:
 lbURidK2mjUFanObyO_xloBt
 * */

$client = new apiClient();
$client -> setApplicationName("Google UserInfo PHP Starter Application");
// Visit https://code.google.com/apis/console?api=plus to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
$client -> setClientId('323742889259.apps.googleusercontent.com');
$client -> setClientSecret('lbURidK2mjUFanObyO_xloBt');
$client -> setRedirectUri('https://web.fe.up.pt/~ei09063/lbaw/API/utilizadores/entrar_google.php');
$client -> setDeveloperKey('AIzaSyARYqssdoPRxp6xjrwHW53EUPjlzUlUjuI');
$oauth2 = new apiOauth2Service($client);
$cal = new apiCalendarService($client);

if (isset($_GET['code'])) {
	$client -> authenticate();
	$_SESSION['token'] = $client -> getAccessToken();
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$client -> setAccessToken($_SESSION['token']);

}

if (isset($_REQUEST['logout'])) {
	unset($_SESSION['token']);
	$client -> revokeToken();
}

if ($client -> getAccessToken()) {

	$user = $oauth2 -> userinfo -> get();
	//var_dump($user);
	// These fields are currently filtered through the PHP sanitize filters.
	// See http://www.php.net/manual/en/filter.filters.sanitize.php
	$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	$img = filter_var($user['picture'], FILTER_VALIDATE_URL);
	$personMarkup = "$email<div><img src='$img?sz=50'></div>";

	$stm = $db -> prepare('Select utilizadorid from utilizador where email=:email');
	$stm -> bindParam(":email", $email);
	if ($stm -> execute()) {
		//echo $personMarkup;
		$result = $stm -> fetch(PDO::FETCH_ASSOC);
		$stm = $db -> prepare('Update utilizador set google_token=:key where utilizadorid=:id');
		$token = $client -> getAccessToken();
		//var_dump($token);

		$stm -> bindParam(":key", $token);
		$stm -> bindParam(":id", $result['utilizadorid']);
		if ($stm -> execute()) {
		} else {echo json_encode('error to db');
		}
	}
	// The access token may have been updated lazily.
	$_SESSION['token'] = $client -> getAccessToken();
} else {
	$authUrl = $client -> createAuthUrl();
}

if (isset($personMarkup)) {
	$result['img'] = $img;
	$result['email'] = $email;
	$result['name'] = $user['name'];
	$result['id'] = $user['id'];
	//echo $personMarkup;
}

if (isset($authUrl)) {
	$result['logged'] = 0;
	$result['url'] = $authUrl;
	echo json_encode($result);
	//echo "<a href=$authUrl>Connect</a>";
} else {
	$result['logged'] = 1;
	if(isset($_REQUEST['session'])){
		$_SESSION['img'] = $result['img'];
	}else{
	//$_SESSION['utilizadorid'] = 'google';
	//$_SESSION['username'] = $result['name'];
	if (isset($_REQUEST['get'])) {
		echo json_encode($result);
	} else {
		echo '<script>window.close()</script>';
	}}
	//echo "<a class='logout' href='?logout'>Logout</a>";
}
?>
