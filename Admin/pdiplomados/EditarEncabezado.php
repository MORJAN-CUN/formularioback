<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $secuencia = $_POST['secuencia'];
    $periodo = $_POST['periodo'];
    $grupo = $_POST['grupo'];
    $centro_costos = $_POST['centro_costos'];
    $programa = $_POST['programa'];

	$result = Diplomados::EditEncabezado($db_conn,$secuencia,$periodo,$grupo,$centro_costos,$programa);

    $id_usuario = $_POST['id_usu'];
    $cedula = $_POST['cedula'];
    $modulo = 'PD';
    $tipo = 'ACTUALIZACION';

    $periodo_edit_audi = $_POST['periodo_edit_audi'];
    $grupo_edit_audi = $_POST['grupo_edit_audi'];
    $programa_edit_audi = $_POST['programa_edit_audi'];
    $centro_costos_edit_audi = $_POST['centro_costos_edit_audi'];

    $descripcion = 'DIPLOMADO: '.$secuencia.'  Se edito el encabezado con los siguientes cambios
    periodo: '.$periodo_edit_audi.' periodo nuevo: '.$periodo.'
    grupo: '.$grupo_edit_audi.' grupo nuevo: '.$grupo.'
    centro costos: '.$centro_costos_edit_audi.' centro costos nuevo: '.$centro_costos.'
    programa: '.$programa_edit_audi.' programa nuevo: '.$programa.'
    ';

    $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);

	echo $result;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}