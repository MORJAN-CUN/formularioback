<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $especifica = $_POST['especifica'];

	$result = AplicacionPagos::getPagosRef($db_conn,$especifica);

	echo json_encode($result);
    
}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}