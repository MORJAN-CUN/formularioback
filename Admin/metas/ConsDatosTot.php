<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id = $_POST['id'];
    $periodo = $_POST['periodo'];
    //$grupo_analisis = $_POST['grupo_analisis'];

	$result = Metas::getTotValues($db_conn,$id,$periodo);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}