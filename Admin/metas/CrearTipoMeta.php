<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre_meta = $_POST['nombre_meta'];
    $clase_ingreso = $_POST['clase_ingreso'];
    $tipo_ingreso = $_POST['tipo_ingreso'];
    $regional = $_POST['regional'];
    $sede = $_POST['sede'];
    $modalidad = $_POST['modalidad'];
    $programa = $_POST['programa'];
    $nivel = $_POST['nivel'];
    $ciclo = $_POST['ciclo'];
    $tipo_alumno = $_POST['tipo_alumno'];
    $grupo = $_POST['grupo'];
    $valor_meta = $_POST['valor_meta'];
    $cantidad_meta = $_POST['cantidad_meta'];

    $result = Metas::InsertTipoMeta($db_conn,$nombre_meta,$clase_ingreso,$tipo_ingreso,$regional,$sede,$modalidad,$programa,$nivel,$ciclo,$tipo_alumno,$grupo,$valor_meta,$cantidad_meta);

    echo $result;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
