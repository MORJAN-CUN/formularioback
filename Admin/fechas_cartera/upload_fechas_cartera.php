<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $data_entrada = $_POST;
    $registros  = $data_entrada['registros'];

    $id_usuario = $data_entrada['id_usu'];
    $cedula = $data_entrada['cedula'];

    $reg_arr = json_decode($registros);

    //Recorrer array

    $result = array();

    foreach($reg_arr as $key){
        
        $cedula_estudiante = $key->cedula_estudiante;
        $periodo = $key->periodo;
        $nota_debito = $key->nota_debito;
        $fecha_vencimiento = $key->fecha_vencimiento;

        $update = FechasCartera::UpdateFecha($db_conn,$cedula_estudiante,$periodo,$nota_debito,$fecha_vencimiento);
        $commit = FechasCartera::commit($db_conn);

        if($update == 1){
            //Ejecutado correctamente

            $modulo = 'AFC';
            $tipo = 'ACTUALIZACION';

            //Auditoria

            $descripcion = 'Se actualizo la fecha de vencimiento de la nota debito: '.$nota_debito.' del estudiante: '.$cedula_estudiante.' en el periodo: '.$periodo.' , fecha vencimiento: '.$fecha_vencimiento;

            $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);

            $resultado_update = array(
                'cedula_estudiante' => $cedula_estudiante,
                'periodo' => $periodo,
                'nota_debito' => $nota_debito,
                'fecha_vencimiento' => $fecha_vencimiento,
                'status' => 1,
                'message' => 'Actualizacion hecha correctamente'
            );

            array_push($result, $resultado_update);

        }else{
            //Ocurrio un error

            $resultado_update = array(
                'cedula_estudiante' => $cedula_estudiante,
                'periodo' => $periodo,
                'nota_debito' => $nota_debito,
                'fecha_vencimiento' => $fecha_vencimiento,
                'status' => 0,
                'message' => 'Ha ocurrido un error, ERROR: '.$update
            );

            array_push($result, $resultado_update);

        }

    }

    echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
