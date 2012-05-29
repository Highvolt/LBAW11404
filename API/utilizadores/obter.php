<?php
	//echo json_encode($_SESSION);
	require_once('../init.php');
	
    if(isset($_SESSION['utilizadorid'])){
		$result['utilizadorid']=$_SESSION['utilizadorid'];
		$result['username']=$_SESSION['username'];
		$result['email']=$_SESSION['email'];
		$result['nome']=$_SESSION['nome'];
		if(isset($_SESSION['img'])){
			$result['img']=$_SESSION['img'];
		}
		echo json_encode($result);
    }else{
    	echo json_encode(FALSE);
    }
?>