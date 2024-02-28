<?php
include_once 'main.controller.php';
//include '../../libs/configAdmin.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $data_entrada = $_POST;
    $registros  = $data_entrada['registros'];

    $id_usuario = $data_entrada['usuario'];
    $descripcion = $data_entrada['descripcion'];
    $archivo = $data_entrada['archivo'];

    $reg_arr = json_decode($registros);

    //Obtener un consecutivo para insertar en la tabla de encabezado
    $consecutivo = Credyty::Consecutivo($db_conn);

    if($consecutivo == '' || $consecutivo == null || empty($consecutivo)){
        $consecutivo = 1;
    }

    //Insertar encabezado 
    $insert_encac = Credyty::Insert_cun_enc_legalizados($db_conn,$consecutivo,$id_usuario,$descripcion,$archivo);

    if($insert_encac == 1){

        //Insertar en detalle -- recorrer array de registros
        foreach($reg_arr as $data_excel){

            //Fecha aprobacion
            $F_APROVACION = $data_excel->fecha_aprobacion_credito;

            //Fecha primer pago
            $F_PRIMERP = $data_excel->fecha_primer_pago;

            //Fecha legalizacion
            $F_LEGALIZACION = $data_excel->fechas_de_legalizacion;
            $F_LEGALIZACION = substr($F_LEGALIZACION, 0, -19);
   
            $MES = substr($F_LEGALIZACION, 5, 2);

            $datos_insert = array(
                'IDENTIFICACION' => $data_excel->nmro_identificacion,
                'VALOR_FINANCIADO' => $data_excel->val_financiado,
                'MES' => $MES,
                'ITEM' => $data_excel->item,
                'NOMBRE' => $data_excel->nombres,
                'PROGRAMA' => $data_excel->programa,
                'SEDE' => $data_excel->sede,
                'F_APROVACION'  => $F_APROVACION,
                'CUOTAS' => $data_excel->numero_cuotas,
                'P_PAGO' => $data_excel->primer_pago,
                'FP_PAGO' => $F_PRIMERP,
                'V_PAGO' => $data_excel->valor_pago_a_universidad,
                'N_ORDEN' => $data_excel->numero_orden_matricula,
                'F_PAGO' => $data_excel->fechas_de_pago,
                'F_LEGALIZACION' => $F_LEGALIZACION,
                'MODALIDAD' => $data_excel->modalidad_estudio,
                'CIUDAD' => $data_excel->ciudad_residencia,
                'SEMESTRE' => $data_excel->semestre,
                'INTERES' => $data_excel->interes_tot_estimado,
                'PERIODO' => $data_excel->periodo_academico,
                'AVALADOR' => $data_excel->avalador,
                'CONSECUTIVO' => $consecutivo,
                'NOTA_CRE' => 0
            );

            //Insertar array en tabla detalle

            $insert_data = Credyty::loadfile($db_conn,$datos_insert);

        }
        
        //Ejecutar Updates
        Credyty::UpdateOne($db_conn,$consecutivo);
        Credyty::UpdateTwo($db_conn,$consecutivo);
        Credyty::UpdateTree($db_conn,$consecutivo);

        $insert_data = 1;

        $result = array(
            'consecutivo' => $consecutivo,
            'status' => $insert_data
        );

    }else{
        $result = array(
            'consecutivo' => null,
            'status' => 0
        );
    }

    echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
