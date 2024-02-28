<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	$factura = $_POST['factura'];
  
	$result = FacturacionE::getDatosF($db_conn,$factura);
	$data = $result[0];

    echo json_encode($data);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}