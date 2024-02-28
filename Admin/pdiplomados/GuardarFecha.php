<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $secuencia = $_POST['secuencia'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    $tot_registros = Diplomados::getTotRegistrosDetalle($db_conn,$secuencia);
    $cuota = $tot_registros + 1;
    $valor_uso = 100/$cuota;
    $valor_uso = round($valor_uso);

    //Insertar registro
    $insert = Diplomados::InsertDetalle($db_conn,$secuencia,$cuota,$fecha_vencimiento,$valor_uso);

    //Consultar registros de la secuencia para hacer un update

    $datos_update = Diplomados::getDetalleEncabezado($db_conn,$secuencia);

    foreach($datos_update as $key){

        $consecutivo = $key['CONSECUTIVO'];
        $update = Diplomados::UpdateDetalle($db_conn,$consecutivo,$valor_uso);

    }

    $id_usuario = $_POST['id_usu'];
    $cedula = $_POST['cedula'];
    $modulo = 'PD';
    $tipo = 'INSERCION';

    $descripcion = 'DIPLOMADO DETALLE: '.$secuencia.'    Se creo una nueva fecha de vencimiento:
    fecha vencimiento: '.$fecha_vencimiento.'
    ';

    $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);

    echo 1;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}