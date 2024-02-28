<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $fecha_desde = $_POST['fecha_desde'];

	$result = AplicacionPagos::getPagosState($db_conn,$fecha_desde);

	echo json_encode($result);
    
}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}