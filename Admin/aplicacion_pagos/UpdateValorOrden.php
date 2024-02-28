<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $referencia = $_POST['referencia'];

    $state= AplicacionPagos::UpdateValorOrden($db_conn,$referencia);
    $commit = AplicacionPagos::commit($db_conn);

    echo $state;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
