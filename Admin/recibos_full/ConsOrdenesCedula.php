<?php
include_once 'main.controller.php';
include '../../libs/config.php';
set_time_limit(500); // 

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $cedula  = $_POST['cedula'];

    $ordenes = RecibosFull::getOrdenesCedula($db_conn,$cedula);

    echo json_encode($ordenes);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
