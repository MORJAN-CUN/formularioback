<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $orden = $_POST['orden'];
    $documento = $_POST['documento'];
    $valor_orden = $_POST['valor_orden'];
    $cedula = $_POST['cedula'];
    $valor_idiomas = $_POST['valor_idiomas'];
    $id_usuario = $_POST['id_usu'];
    $cedula_audi = $_POST['cedula_aud'];
    $modulo = 'RF';
    $tipo = 'ACTUALIZACION';
    $valor_orden_act = $_POST['valor_orden_act'];
    $valor_idiomas_act = $_POST['valor_idiomas_act'];

    $descripcion = 'ORDEN: '.$orden.'    Se actualizo la orden con los siguientes cambios: 
    VALOR ORDEN: '.$valor_orden_act.' VALOR ORDEN NUEVO: '.$valor_orden.'     
    VALOR IDIOMAS: '.$valor_idiomas_act.' VALOR IDIOMAS NUEVO: '.$valor_idiomas;

    $auditoria = InsertLog($db_conn,$cedula_audi,$id_usuario,$modulo,$tipo,$descripcion);

    $commit = RecibosFull::commit($db_conn);

    $update_detalle = RecibosFull::UpdateDetalle($db_conn,$orden,$documento,$valor_orden);
    
    $commit = RecibosFull::commit($db_conn);

    if($update_detalle == 1){

        $update_principal = RecibosFull::UpdatePrincipal($db_conn,$orden,$documento,$valor_orden);

        $commit = RecibosFull::commit($db_conn);

        if($update_principal == 1){

            //Hacer update idiomas

            $update_idiomas = RecibosFull::UpdateIdiomas($db_conn,$cedula,$valor_idiomas,$documento,$orden);

            $commit = RecibosFull::commit($db_conn);

            if($update_idiomas == 1){

                $function_recibo = RecibosFull::GenerarRecibo($db_conn,$orden,$documento,$valor_orden);

                $commit = RecibosFull::commit($db_conn);

                if($function_recibo == 1){
                    $result = 1;
                }else{
                    //Dejar valores como estaban
                    $result = 0;
                }
                
            }else{
                $result = 0;
            }

        }else{
            $result = 0;
        }

    }else{
        $result = 0;
    }

    if($result == 1){
        echo 1;
    }else{
        echo 0;
    }

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
