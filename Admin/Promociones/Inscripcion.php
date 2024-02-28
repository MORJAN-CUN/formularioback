<?php
include_once '../controllers/promociones.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$periodo = $_POST['periodo'];
	$programa = $_POST['programa'];
	$ciclo = $_POST['ciclo'];

	$result = ParametrosController::getInscripcion($db_conn,$periodo,$programa,$ciclo);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
