<?php
//session_save_path('/tmp/');
session_set_cookie_params ( 0 , '/~ei09063/');
session_start();
if($_REQUEST['debug']){
ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
error_reporting(E_ALL);
}

 $user='lbaw11404';
 $pass='lbawsampso';

    $db = new PDO('pgsql:host=vdbm.fe.up.pt;dbname=lbaw11404', $user, $pass);
   
    
	if($db===NULL){
    
    die("not connected");
    }
	
?>