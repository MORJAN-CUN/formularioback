<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $periodo = $_POST['periodo'];
    $grupo = $_POST['grupo'];
    $centro_costos = $_POST['centro_costos'];
    $programa = $_POST['programa'];
    $valor_uso = $_POST['valor_uso'];
    $linea_credito = $_POST['linea_credito'];
   
	
	$result = Diplomados::InsertEncabezado($db_conn,$periodo,$grupo,$centro_costos,$programa,$valor_uso,$linea_credito);

    //Guardar auditoria

    //Consultar id de la secuencia insertada

    $id_diplomado = Diplomados::getSecuenciaInsert($db_conn);

    $id_usuario = $_POST['id_usu'];
    $cedula = $_POST['cedula'];
    $modulo = 'PD';
    $tipo = 'INSERCION';

    $descripcion = 'DIPLOMADO: '.$id_diplomado.'    Se creo el encabezado del diplomado con los siguientes valores:
    periodo: '.$periodo.'
    grupo: '.$grupo.'
    centro de costos: '.$centro_costos.'
    programa: '.$programa.'
    porcentaje: '.$valor_uso.'
    linea credito: '.$linea_credito.'
    ';

    $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);

	echo $result;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}