<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //Cargar remuneracion

    $Remuneracion = FacturacionE::Remuneracion($db_conn);

    echo $Remuneracion;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}