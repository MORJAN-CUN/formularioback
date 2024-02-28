<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $unidad_negocio = $_POST['unidad_negocio'];
    $periodo = $_POST['periodo'];
    $grupo_analisis = $_POST['grupo_analisis'];

	$result = Metas::getMetasCreadas($db_conn,$unidad_negocio,$periodo,$grupo_analisis);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}