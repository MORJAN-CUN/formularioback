<?php
include_once 'controllers/main.controller.php';
include 'libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$info = json_decode($_POST['info'],true);
	
	$registraPromocion = new ServiceController();
	echo json_encode($registraPromocion->registraPromocion($db_conn, $info));

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
