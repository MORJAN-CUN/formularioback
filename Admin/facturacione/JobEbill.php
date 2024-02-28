<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	$factura = $_POST['factura'];

	$result = FacturacionE::getDatosF($db_conn,$factura);
	$data = $result[0];
    $identificacion = $data['CLIENTE'];

    //Consultar documento electronico
    $result_doc = FacturacionE::getDocElectronico($db_conn,$identificacion);
    $data_doc = $result_doc[0];
    $documento_electronico = $data_doc['DOCUMENTO_ELECTRONICO'];

    $jobebill = FacturacionE::JobEbill($db_conn,$documento_electronico);

    echo $jobebill;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}