<?php
include_once 'controllers/promosiones.controller.php';
include '../../libs/config.php';


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$dato = 'null';

	$result = ParametrosController::getPromociones($db_conn,$dato);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
