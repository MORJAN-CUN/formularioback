<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $periodo = $_POST['periodo'];
    $grupo = $_POST['grupo'];
    $centro_costos = $_POST['centro_costos'];
    $programa = $_POST['programa'];

	$result = Diplomados::getEncabezados($db_conn,$periodo,$grupo,$centro_costos,$programa);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}