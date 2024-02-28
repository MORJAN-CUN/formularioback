<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id = $_POST['id'];
    $nombre_meta = $_POST['nombre_meta'];
    $modalidad = $_POST['modalidad'];
    $programa = $_POST['programa'];
    $nivel = $_POST['nivel'];
    $ciclo = $_POST['ciclo'];
    $tipo_alumno = $_POST['tipo_alumno'];
    $valor_ingresos = $_POST['valor_ingresos'];
    $cantidad_estudiantes = $_POST['cantidad_estudiantes'];

    $result = Metas::UpdateUnidadNegocio($db_conn,$id,$nombre_meta,$modalidad,$programa,$nivel,$ciclo,$tipo_alumno,$valor_ingresos,$cantidad_estudiantes);

    echo $result;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
