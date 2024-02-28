<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $registros = $_POST['registros'];
    $tipo_valor = $_POST['tipo_valor'];

    $id_usuario = $_POST['id_usu'];
    $cedula = $_POST['cedula'];
    $modulo = 'RF';
    $tipo = 'ACTUALIZACION';

    $reg_arr = explode(',' ,$registros);

    $array_result = array();

    foreach ($reg_arr as $key){
        $orden = $key;
        
        //Obtener datos de la orden

        $datos_result = RecibosFull::getDatosOrden($db_conn,$orden);

        $datos_orden = $datos_result[0];

        $periodo = $datos_orden['PERIODO'];
        $cod_modalidad = $datos_orden['COD_MODALIDAD'];
        $cod_programa = $datos_orden['COD_PROGRAMA'];
        $cod_ciclo = $datos_orden['COD_CICLO'];
        $documento = $datos_orden['DOCUMENTO'];
        $valor_orden_act = $datos_orden['VALOR_ORDEN'];

        //Consultar valor nuevo y antiguo

        $valor_orden_arr = RecibosFull::getValorFull($db_conn,$periodo,$cod_modalidad,$cod_programa,$cod_ciclo);
        $valor_key = $valor_orden_arr[0];

        if($tipo_valor == 'nuevo'){
            $valor_orden = $valor_key['NUEVO'];
        }else{
            $valor_orden = $valor_key['ANTIGUO'];
        }

        $descripcion = 'ORDEN: '.$orden.'    Se actualizo la orden con los siguientes cambios: 
        VALOR ORDEN: '.$valor_orden_act.' VALOR ORDEN NUEVO: '.$valor_orden;

        $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);

        //Hacer updates por el nuevo valor

        $update_detalle = RecibosFull::UpdateDetalle($db_conn,$orden,$documento,$valor_orden);

        $update_detalle = 1;

        if($update_detalle == 1){

            $update_principal = RecibosFull::UpdatePrincipal($db_conn,$orden,$documento,$valor_orden);

            $update_principal = 1;

            if($update_principal == 1){

                //Ejecutar funcion

                $function_recibo = RecibosFull::GenerarRecibo($db_conn,$orden,$documento,$valor_orden);

                $function_recibo = 1;

                if($function_recibo == 1){

                    $result = array(
                        'status' => 1,
                        'orden' => $orden,
                        'valor_aplicado' => $valor_orden,
                        'message' => 'Orden actualizada correctamente'
                    );

                    array_push($array_result, $result);

                }else{
                    //Dejar valores como estaban
                   
                    $result = array(
                        'status' => 0,
                        'orden' => $orden,
                        'valor_aplicado' => $valor_orden,
                        'message' => 'Error al generar el recibo, se reversan los updates'
                    );

                    array_push($array_result, $result);

                }


            }else{
                
                $result = array(
                    'status' => 0,
                    'orden' => $orden,
                    'valor_aplicado' => $valor_orden,
                    'message' => 'Error al realizar el update a tabla orden'
                );

                array_push($array_result, $result);

            }

        }else{

            $result = array(
                'status' => 0,
                'orden' => $orden,
                'valor_aplicado' => $valor_orden,
                'message' => 'Error al realizar el update a tabla detalle_orden'
            );

            array_push($array_result, $result);

        }

    }

    echo json_encode($array_result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
