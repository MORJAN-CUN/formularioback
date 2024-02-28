<?php
include_once '../controllers/promociones.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $periodo = $_POST['periodo'];

	$result = ParametrosController::getPromocionesCreadasPeriodo($db_conn,$periodo);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
