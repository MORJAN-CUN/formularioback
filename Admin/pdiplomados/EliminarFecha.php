<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id_detalle = $_POST['id_detalle'];
    $secuencia_cab = $_POST['secuencia_cab'];

    $delete = Diplomados::DeleteDetalle($db_conn,$secuencia_cab,$id_detalle);

    //Consultar totales y recalcular

    $tot_registros = Diplomados::getTotRegistrosDetalle($db_conn,$secuencia_cab);
    $cuota = $tot_registros;
    $valor_uso = 100/$cuota;
    $valor_uso = round($valor_uso);

    //Consultar registros de la secuencia para hacer un update

    $datos_update = Diplomados::getDetalleEncabezadoDelete($db_conn,$secuencia_cab);

    $cont = 0;

    foreach($datos_update as $key){

        $cont++;
        $cuota_new = $cont;

        $consecutivo = $key['CONSECUTIVO'];
        $update = Diplomados::UpdateDetalleDelete($db_conn,$cuota_new,$consecutivo,$valor_uso);

    }

    $id_usuario = $_POST['id_usu'];
    $cedula = $_POST['cedula'];
    $modulo = 'PD';
    $tipo = 'ELIMINACION';

    $descripcion = 'DIPLOMADO DETALLE: '.$id_detalle.'    Se elimino la fecha de vencimiento';

    $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);

    echo 1;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}