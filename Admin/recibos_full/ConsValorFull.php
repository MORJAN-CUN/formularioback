<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $periodo = $_POST['periodo'];
    $cod_modalidad = $_POST['cod_modalidad'];
    $cod_programa = $_POST['cod_programa'];
    $cod_ciclo = $_POST['cod_ciclo'];

    $valor_full = RecibosFull::getValorFull($db_conn,$periodo,$cod_modalidad,$cod_programa,$cod_ciclo);

    echo json_encode($valor_full);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
