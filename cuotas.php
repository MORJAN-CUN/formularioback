<?php
include_once 'controllers/main.controller.php';
include 'libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$promocion = $_POST['promocion'];
	$periodo = $_POST['periodo'];
	$programa = $_POST['programa'];
	$ciclo = $_POST['ciclo'];
	$tipoFormacion = $_POST['tipoFormacion'];
	$valorMatricula = $_POST['valorMatricula'];

	$cuotas = new ServiceController();
	echo json_encode($cuotas->cuotas($db_conn, $promocion, $periodo, $programa, $ciclo, $tipoFormacion, $valorMatricula));

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
