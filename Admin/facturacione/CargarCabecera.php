<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	$factura = $_POST['factura'];

    //Consultar periodo

	$result = FacturacionE::getDatosF($db_conn,$factura);
	$data = $result[0];
    $fecha_inicial = $data['FECHA_INICIAL'];
    $fecha_final = $data['FECHA_VENCIMIENTO'];
    $clase = null;
    $secuencia_persona = null;
    $identificacion = $data['CLIENTE'];
    $documento = $data['DOCUMENTO'];
    $nmro_credito = $data['LIQUIDACION'];

    //Cargar movimiento del mes

    $cabecera = FacturacionE::Cabecera($db_conn,$fecha_inicial,$fecha_final,$clase,$secuencia_persona,$identificacion,$documento,$nmro_credito);

    echo json_encode($cabecera);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}