<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $secuencia = $_POST['secuencia'];
    
	$fecha_max = Diplomados::getFechaMaxEncabezado($db_conn,$secuencia);

	if($fecha_max == '' || $fecha_max == null || empty($fecha_max)){
        echo "Ninguna";
    }else{
        echo $fecha_max;
    }

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}