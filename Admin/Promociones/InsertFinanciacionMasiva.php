<?php
include_once '../controllers/promociones.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $registros  = $_POST['registros'];
    $periodo_new = $_POST['periodo'];

    $reg_array = explode(',', $registros);

    $result_gen = array();

    foreach($reg_array as $reg_new){

        //Obtener id secuencia
        $secuencia = $reg_new;

        //Obtener datos del registro

        $datos_reg = ParametrosController::getDataFinanciacion($db_conn,$secuencia);

        $data = $datos_reg[0];

        $estado = $data['ESTADO'];
        $fecha_registro = date('Y-m-d');
        $tipo_promocion = $data['TIPO_PROMOCION'];
        $periodo = $periodo_new;
        $periodo_idiomas = $data['PERIODO_IDIOMAS'];
        $programa = $data['PROGRAMA'];
        $ciclo = $data['CICLO'];
        $tipo_inscripcion = $data['TIPO_INSCRIPCION'];
        $valor_matricula = $data['VALOR_MATRICULA'];
        $valor_idiomas = $data['VALOR_IDIOMAS'];
        $valor_servicio = $data['VALOR_SERVICIO'];
        $numero_cuotas = $data['NUMERO_CUOTAS'];
        $porc_matricula = $data['PORC_MATRICULA'];
        $porc_idiomas = $data['PORC_IDIOMAS'];
        $val_traslado_matricula = $data['VAL_TRASLADO_MATRICULA'];
        $val_traslado_idiomas = $data['VAL_TRASLADO_IDIOMAS'];
        $val_prom_beneficiario = $data['VAL_PROM_BENEFICIARIO'];
        $es_cun_vive = $data['ES_CUN_VIVE'];
        $es_2x1 = $data['ES_2X1'];
        
        //Validar si ya existe o no el registro
        
        $exist = ParametrosController::ExistPromocion($db_conn,$tipo_promocion,$periodo,$periodo_idiomas,$programa,$ciclo,$tipo_inscripcion);

        if($exist == 1){

            $array_r = array(
                'secuencia_ant' => $secuencia,
                'secuencia_new' => null,
                'periodo_new' => $periodo_new,
                'status' => 0,
                'message' => 'Error, el registro ya existe en la base de datos, no se puede crear para el periodo: '.$periodo
            );

            array_push($result_gen, $array_r);

        }else{

            $id = ParametrosController::Selecsecuencia($db_conn);
            
            $insert_reg = ParametrosController::InsertPromocionMasivo($db_conn,$estado,$fecha_registro,$tipo_promocion,$periodo,$periodo_idiomas,
            $programa,$ciclo,$tipo_inscripcion,$valor_matricula,$valor_idiomas,$valor_servicio,$numero_cuotas,$porc_matricula,$porc_idiomas,
            $id,$es_cun_vive,$es_2x1);

            $secuencia_new_gen = 1;

            if($insert_reg == 1){

                $array_r = array(
                    'secuencia_ant' => $secuencia,
                    'secuencia_new' => $secuencia_new_gen,
                    'periodo_new' => $periodo_new,
                    'status' => 1,
                    'message' => 'Creado correctamente en la base de datos, con el periodo: '.$periodo
                );

            }else{

                $array_r = array(
                    'secuencia_ant' => $secuencia,
                    'secuencia_new' => null,
                    'periodo_new' => $periodo_new,
                    'status' => 0,
                    'message' => 'Error desconocido al insertar en la base de datos'
                );

            }

            array_push($result_gen, $array_r);

        }

    }

	echo json_encode($result_gen);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
