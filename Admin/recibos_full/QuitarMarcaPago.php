<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $orden = $_POST['orden'];
    $periodo = $_POST['periodo'];
    $cedula = $_POST['cedula'];

    //Datos auditoria

    $id_usuario = $_POST['id_usu'];
    $cedula_audi = $_POST['cedula_audi'];
    $modulo = 'RF';
    $tipo = 'ACTUALIZACION';

    $descripcion = 'ORDEN: '.$orden.'    Se quito la marca de pago';

    $auditoria = InsertLog($db_conn,$cedula_audi,$id_usuario,$modulo,$tipo,$descripcion);

    $marca_pago = RecibosFull::QuitarMarcaPago($db_conn,$periodo,$cedula);
    $commit = RecibosFull::commit($db_conn);

    echo $marca_pago;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
