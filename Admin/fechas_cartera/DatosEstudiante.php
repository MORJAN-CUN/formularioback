<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $cedula = $_POST['cedula'];

    $datos = FechasCartera::getDatosEstudiante($db_conn,$cedula);
  
    echo json_encode($datos);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
