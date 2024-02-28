<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $cedula_estudiante = $_POST['cedula_estudiante'];
    $periodo = $_POST['periodo'];
    $nota_debito = $_POST['nota_debito'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    $id_usuario = $_POST['id_usu'];
    $cedula = $_POST['cedula'];

    $update = FechasCartera::UpdateFecha($db_conn,$cedula_estudiante,$periodo,$nota_debito,$fecha_vencimiento);
    $commit = FechasCartera::commit($db_conn);

    $modulo = 'AFC';
    $tipo = 'ACTUALIZACION';

    //Auditoria

    $descripcion = 'Se actualizo la fecha de vencimiento de la nota debito: '.$nota_debito.' del estudiante: '.$cedula_estudiante.' en el periodo: '.$periodo.' , fecha vencimiento: '.$fecha_vencimiento;

    $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);

    echo $update;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
