<?php
include_once 'main.controller.php';
include '../../libs/config.php';
set_time_limit(500); // 

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $periodo = $_POST['periodo'];
    $cedula  = $_POST['cedula'];
    $programa = $_POST['programa'];
    $ciclo = $_POST['ciclo'];

    $ordenes = RecibosFull::getOrdenes($db_conn,$periodo,$cedula,$programa,$ciclo);

    echo json_encode($ordenes);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
