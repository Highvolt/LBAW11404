<?php
require_once '../init.php';

$fetched = 0;
$old=-1;
if (isset($_SESSION['token'])) {
	$stm = $db -> prepare('Select * from google_calendar where idutilizador=:userid');
	$stm -> bindParam(":userid", $_SESSION['utilizadorid']);
	if ($stm -> execute()) {
		$fetched = $stm -> fetch(PDO::FETCH_ASSOC);
		if ($fetched == FALSE) {
			die(json_encode("no google db"));
		}
	} else {
		die(json_encode("fail db"));
	}

	$calid = 0;
	if (is_array($fetched)) {
		$date = strtotime($fetched['lastupdate']);
		$calid = $fetched['id'];
		//'Y-m-d H:i:s*',
		$cur = time();
		$array_cur = getdate($cur);
		//var_dump($array_cur);
		$old = $cur - $date;
//		$result['age'] = $old;

	}
	$dim = cal_days_in_month(CAL_GREGORIAN, $_REQUEST['month'], $_REQUEST['year']);
	if (isset($_REQUEST['month']) && isset($_REQUEST['year'])) {
		$di=$_REQUEST['year'].'-'.$_REQUEST['month'].'-01 00:00:00';
		
		$df=$_REQUEST['year'].'-'.$_REQUEST['month'].'-'.$dim.' 23:59:59';
		$stm = $db -> prepare('Select name, startdate, enddate from google_event where id_google_cal=:idcal and ((startdate>=:di and startdate<=:df) or (enddate>=:di and enddate<=:df) ) ');
		$stm -> bindParam(":idcal", $calid);
		$stm -> bindParam(":di", $di);
		$stm -> bindParam(":df",$df);
		if ($stm -> execute()) {
			$fetched = $stm -> fetchAll(PDO::FETCH_ASSOC);
			if ($fetched == FALSE) {
				die(json_encode("Sem eventos do google"));
			}
		} else {
			die(json_encode("fail db"));
		}
	}
	$calendar=$fetched;
	$result=array();
	for ($i=0; $i <= $dim+1; $i++) { 
		array_push($result,array());
	}
	
	

	foreach ($calendar as $key => $value) {
		$tmps = date_parse($value['startdate']);
		if ($_REQUEST['debug'] == 1) {
			if ($tmps == FALSE) {
				echo $value['start']['date'] . '<br/>';
			}
		}
		$tmpe = date_parse($value['enddate']);
		if ($tmps['month'] == $_REQUEST['month'] && $tmps['year'] == $_REQUEST['year']) {
			array_push($result[$tmps['day']], $value);
		} else {
			if ($tmpe['month'] == $_REQUEST['month'] && $tmpe['year'] == $_REQUEST['year']) {
				array_push($result[$tmpe['day']], $value);
			}
		}
	}
	echo json_encode($result);

}
 