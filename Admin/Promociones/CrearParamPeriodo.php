<?php
include_once '../controllers/promociones.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$periodo = $_POST['periodo'];
	$tipoPromocion = $_POST['tipoPromocion'];
	$fechaRegistro = $_POST['fechaRegistro'];
	$fechaFinal = $_POST['fechaFinal'];
	$periodoIdiomas = $_POST['periodoIdiomas'];
	$cuotas = $_POST['cuotas'];
	$porcpagar = $_POST['porcpagar'];

	$validate = ParametrosController::existPeriodo($db_conn,$periodo,$tipoPromocion);

	if($validate == 1){
		echo 2;
	}else{

		$result = ParametrosController::InsertParamPeriodo($db_conn,$periodo,$tipoPromocion,$fechaRegistro,$fechaFinal,$periodoIdiomas,$cuotas,$porcpagar);

		echo json_encode($result);

	}

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
