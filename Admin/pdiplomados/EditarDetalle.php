<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id_detalle = $_POST['id_detalle'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $fecha_vencimiento_audi = $_POST['fecha_vencimiento_audi'];

    $update = Diplomados::UpdateDetalleFecha($db_conn,$id_detalle,$fecha_vencimiento);

    $id_usuario = $_POST['id_usu'];
    $cedula = $_POST['cedula'];
    $modulo = 'PD';
    $tipo = 'ACTUALIZACION';

    $descripcion = 'DIPLOMADO DETALLE: '.$id_detalle.'    Se edito la fecha de vencimiento:
    fecha vencimiento: '.$fecha_vencimiento_audi.' fecha vencimiento nueva: '.$fecha_vencimiento.'
    ';

    $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);

    echo $update;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}