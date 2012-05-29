<?php
if(isset($status['silent'])){
	ob_start();	
}
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
	$client -> setAccessToken($_SESSION['token']);
}

if ($client -> getAccessToken()) {
				$created=0;
			$updated=0;
			$deleted=0;
	$stm = $db -> prepare('Select * from google_calendar where idutilizador=:userid');
	$stm -> bindParam(":userid", $_SESSION['utilizadorid']);
	if ($stm -> execute()) {
		$fetched = $stm -> fetch(PDO::FETCH_ASSOC);
		if ($fetched == FALSE) {
			$db -> beginTransaction();
			$stm = $db -> prepare('Insert into google_calendar (idutilizador,lastupdate,in_use) values (:user,NOW(),TRUE)');
			$stm -> bindParam(":user", $_SESSION['utilizadorid']);
			if ($stm -> execute()) {
				$db -> commit();
				$stm = $db -> prepare('Select * from google_calendar where idutilizador=:userid');
				$stm -> bindParam(":userid", $_SESSION['utilizadorid']);
				if ($stm -> execute()) {
					$fetched = $stm -> fetch(PDO::FETCH_ASSOC);
				}
			} else {
				die(json_encode("Falhou a adicionar um base de dados de calendario local"));
			}
		}
		if (is_array($fetched)) {
			$date = strtotime($fetched['lastupdate']);
			//'Y-m-d H:i:s*',
			$cur = time();
			$array_cur=getdate($cur);
			//var_dump($array_cur);
			$old = $cur - $date;
			$result['age'] = $old;
			if (((isset($_REQUEST['force']) and $old < 3600 and $old>5) or (!isset($_REQUEST['force']) and $old < 172800 and $old>5) )and !isset($_REQUEST['debug1'])) {
					if(isset($_REQUEST['force'])){
						die(json_encode(array("wait" => 3600 - $old)));}
					else {
						die(json_encode(array("wait" => 172800 - $old)));
					}
			}
			$db -> beginTransaction();
			$stm = $db -> prepare('UPDATE google_calendar SET lastupdate=NOW() where idutilizador=:user');
			$stm -> bindParam(":user", $_SESSION['utilizadorid']);
			$stm -> execute();
			$db -> commit();

			$calList = $cal -> calendarList -> listCalendarList();
			$optParams = array('timeMin' => $array_cur['year'] . '-' . $array_cur['mon'] . '-01T00:00:00+0000');
			if(isset($_REQUEST['debug1'])){
					var_dump($calList);
				echo '<br/><br/>';
				}
			foreach ($calList['items'] as $key => $value) {

				$calendar = $cal -> events -> listEvents($value['id'], $optParams);
				if(isset($_REQUEST['debug1'])){
					var_dump($calendar);
					echo '<br/><br/>';
				}
				foreach ($calendar['items'] as $key => $value) {
					$id=$value['id'];
					$eventname=$value['summary'];
					if(isset($value['start']['dateTime'])){
						$date_start=strtotime($value['start']['dateTime']);
						$date_end=strtotime($value['end']['dateTime']);
					}else if(isset($value['start']['date'])){
						$date_start=strtotime($value['start']['date']);
						$date_end=strtotime($value['end']['date']);
					}
					//1999-01-08 04:05:06
					$ds=strftime("%Y-%m-%d %H:%M:%S",$date_start);
					$de=strftime("%Y-%m-%d %H:%M:%S",$date_end);
					
					//echo $id.' eventname: '.$eventname.' start: '.$ds.' end: '.$de.'<br />';
					
					$stm = $db -> prepare('UPDATE google_event SET (name,startdate,enddate)=(:name,:start,:end) WHERE id_google_cal=:idcal and idgevent=:ide');
					$stm -> bindParam(":name", $eventname);
					$stm -> bindParam(":end", $de);
					$stm -> bindParam(":start", $ds);
					$stm -> bindParam(":idcal", $fetched['id']);
					$stm -> bindParam(":ide", $id);
					$stm -> execute();
					if($stm -> rowCount()==0){
						$stm = $db -> prepare('Insert into google_event (id_google_cal,idgevent,name,startdate,enddate) VALUES (:idcal,:ide,:name,:start,:end)');
						$stm -> bindParam(":name", $eventname);
						$stm -> bindParam(":end", $de);
						$stm -> bindParam(":start", $ds);
						$stm -> bindParam(":idcal", $fetched['id']);
						$stm -> bindParam(":ide", $id);
						if($stm -> execute()){
							$created++;
						}
					}else{
						$updated++;
					}
				}
			}
		}

	}
	$result['created']=$created;
	$result['deleted']=$deleted;
	$result['updated']=$updated;
	if(!isset($status['silent'])){
	echo json_encode($result);
	}
	/*$calList = $cal -> calendarList -> listCalendarList();

	 //print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
	 $dim = cal_days_in_month(CAL_GREGORIAN, $_REQUEST['month'], $_REQUEST['year']);
	 $result = array();
	 for ($i = 0; $i <= $dim + 1; $i++) {
	 array_push($result, array());
	 }

	 $optParams = array('timeMin' => $_REQUEST['year'] . '-' . $_REQUEST['month'] . '-01T00:00:00+0000', 'timeMax' => $_REQUEST['year'] . '-' . $_REQUEST['month'] . '-' . $dim . 'T23:59:59+0000');
	 foreach ($calList['items'] as $key => $value) {

	 $calendar = $cal -> events -> listEvents($value['id'], $optParams);
	 if (!isset($_REQUEST['mode2'])) {
	 foreach ($calendar['items'] as $key => $value) {
	 $tmps = date_parse($value['start']['date']);
	 if ($_REQUEST['debug'] == 1) {
	 if ($tmps == FALSE) {
	 echo $value['start']['date'] . '<br/>';
	 }
	 }
	 $tmpe = date_parse($value['end']['date']);
	 if ($tmps['month'] == $_REQUEST['month'] && $tmps['year'] == $_REQUEST['year']) {
	 array_push($result[$tmps['day']], $value);
	 } else {
	 if ($tmpe['month'] == $_REQUEST['month'] && $tmpe['year'] == $_REQUEST['year']) {
	 array_push($result[$tmpe['day']], $value);
	 }
	 }
	 }
	 } else {
	 array_push($result, $calendar);
	 }
	 //$result[$key]=$calendar;
	 //print "<h1>Calendar</h1><pre>" . print_r($calendar, true) . "</pre>";
	 }
	 //print "<h1>Calendar</h1><pre>" .print_r($result,true). "</pre>";
	 echo json_encode($result);
	 */
	$_SESSION['token'] = $client -> getAccessToken();
	if(isset($status['silent'])){
	ob_clean();
	}
}
?>
