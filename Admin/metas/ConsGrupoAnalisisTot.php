<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $unidad_negocio = $_POST['unidad_negocio'];
    $periodo = $_POST['periodo'];

	$result = Metas::getGruposAnalisisTot($db_conn,$unidad_negocio,$periodo);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}