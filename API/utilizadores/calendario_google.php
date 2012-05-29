<?php
require_once '../init.php';
require_once '../../Google_API/src/apiClient.php';
require_once '../../Google_API/src/contrib/apiCalendarService.php';
$client = new apiClient();
$client -> setApplicationName("Google UserInfo PHP Starter Application");
// Visit https://code.google.com/apis/console?api=plus to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
$client -> setClientId('323742889259.apps.googleusercontent.com');
$client -> setClientSecret('lbURidK2mjUFanObyO_xloBt');
$client -> setRedirectUri('https://web.fe.up.pt/~ei09063/lbaw/API/utilizadores/entrar_google.php');
$client -> setDeveloperKey('AIzaSyARYqssdoPRxp6xjrwHW53EUPjlzUlUjuI');

$cal = new apiCalendarService($client);


if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
  $calList = $cal->calendarList->listCalendarList();
	
  //print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
  $dim = cal_days_in_month(CAL_GREGORIAN, $_REQUEST['month'], $_REQUEST['year']);
  $result=array();
	for ($i=0; $i <= $dim+1; $i++) { 
		array_push($result,array());
	}

  $optParams = array('timeMin' => $_REQUEST['year'].'-'.$_REQUEST['month'].'-01T00:00:00+0000','timeMax' => $_REQUEST['year'].'-'.$_REQUEST['month'].'-'.$dim.'T23:59:59+0000');
  foreach ($calList['items'] as $key => $value) {
  		
       $calendar=$cal->events->listEvents($value['id'],$optParams);
	  if(!isset($_REQUEST['mode2'])){
	  foreach ($calendar['items'] as $key => $value) {
		  $tmps=date_parse($value['start']['date']);
		  if($_REQUEST['debug']==1){
		  if($tmps==FALSE){
		  	echo $value['start']['date'].'<br/>';
		  			  }
}
		  $tmpe=date_parse($value['end']['date']);
		  if($tmps['month']==$_REQUEST['month'] && $tmps['year']==$_REQUEST['year']){
		  	array_push($result[$tmps['day']],$value);
		  }else{
		  	if($tmpe['month']==$_REQUEST['month'] && $tmpe['year']==$_REQUEST['year']){
		  		array_push($result[$tmpe['day']],$value);
		  	}
		  }
	  }
	  }else{
	  array_push($result,$calendar);
	  }
		//$result[$key]=$calendar;
		//print "<h1>Calendar</h1><pre>" . print_r($calendar, true) . "</pre>";
  }
  //print "<h1>Calendar</h1><pre>" .print_r($result,true). "</pre>";
 echo json_encode($result);


$_SESSION['token'] = $client->getAccessToken();
} 