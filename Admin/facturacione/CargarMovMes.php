<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	$factura = $_POST['factura'];
    $id_usuario = $_POST['id_usu'];
    $cedula = $_POST['cedula'];
    $modulo = 'FE';
    $tipo = 'ENVIO';

    //Auditoria

    $descripcion = 'FACTURA: '.$factura.'    Se cargo la factura en EBILL';

    $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);

    //Consultar periodo

	$result = FacturacionE::getDatosF($db_conn,$factura);
	$data = $result[0];
    $periodo_m = $data['PERIODO_M'];

    //Cargar movimiento del mes

    $movimiento_mes = FacturacionE::MovimientoMes($db_conn,$periodo_m);

    echo $movimiento_mes;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}