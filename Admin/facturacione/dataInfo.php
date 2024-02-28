<?php
include_once 'main.controller.php';
include '../../libs/config.php';
set_time_limit(500); // 

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $num_factura = $_POST['num_factura'];

    $info = FacturacionE::getDataInfo($db_conn, $num_factura);
    echo json_encode($info);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
