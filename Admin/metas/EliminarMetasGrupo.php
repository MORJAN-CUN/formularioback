<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $unidad_negocio = $_POST['unidad_negocio'];
    $grupo_analisis = $_POST['grupo_analisis'];
    $periodo = $_POST['periodo'];

	$result = Metas::deleteMetasGrupo($db_conn,$unidad_negocio,$grupo_analisis,$periodo);
    $commit = Metas::commit($db_conn);

	echo $result;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}