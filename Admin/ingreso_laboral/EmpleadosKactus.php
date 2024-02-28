<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	$centro_costo = $_POST['centro_costo'];
	$estado = $_POST['estado'];
	$palabra_clave = $_POST['palabra_clave'];

	$result = IngresoLaboral::getEmpleados($db_conn,$centro_costo,$estado,$palabra_clave);

	echo json_encode($result);
    
}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}