<?php
include_once '../controllers/promociones.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$id = $_POST['id'];

	$result = ParametrosController::getDatosPeriodos($db_conn,$id);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
