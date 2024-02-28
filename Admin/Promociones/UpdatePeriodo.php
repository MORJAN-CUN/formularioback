<?php
include_once '../controllers/promociones.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$id_periodo = $_POST['id_periodo'];
	$periodo = $_POST['periodo'];
	$tipo_financiacion = $_POST['tipo_financiacion'];
	$fecha_registro = $_POST['fecha_registro'];
	$fecha_final = $_POST['fecha_final'];
	$periodo_idiomas = $_POST['periodo_idiomas'];
	$numero_cuotas = $_POST['numero_cuotas'];
	$porc_a_pagar = $_POST['porc_a_pagar'];

	$periodoedit_antes = $_POST['periodoedit_antes'];
	$tipofinanciacionedit_antes = $_POST['tipofinanciacionedit_antes'];

	if($periodoedit_antes == $periodo && $tipofinanciacionedit_antes == $tipo_financiacion){

		//Edit sin los primeros campos

		$tipoupd = 1;

		$result = ParametrosController::UpdatePeriodo($db_conn,$id_periodo,$periodo,$tipo_financiacion,$fecha_registro,$fecha_final,$periodo_idiomas,$numero_cuotas,$porc_a_pagar,$tipoupd);

		echo json_encode($result);

	}else{

		//Validar si ya existe un registro con el mismo periodo y el mismo tipo de financiacion

		$validate = ParametrosController::existPeriodo($db_conn,$periodo,$tipo_financiacion);

		if($validate == 1){
			echo 2;
		}else{
			
			$tipoupd = 2;

			$result = ParametrosController::UpdatePeriodo($db_conn,$id_periodo,$periodo,$tipo_financiacion,$fecha_registro,$fecha_final,$periodo_idiomas,$numero_cuotas,$porc_a_pagar,$tipoupd);

			echo json_encode($result);
		}


	}

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
