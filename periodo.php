<?php
include_once 'controllers/main.controller.php';
include 'libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$promocion = $_POST['promocion'];

	$periodo = new ServiceController();
	echo json_encode($periodo->periodo($db_conn, $promocion));

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
