<?php
include_once 'controllers/main.controller.php';
//include '../libs/configAdmin.php';
include '../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$estado = $_POST['estado'];
	$accesos = $_POST['accesos'];

	$result = AdminController::UpdatePerfil($db_conn,$id,$nombre,$estado,$accesos);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
