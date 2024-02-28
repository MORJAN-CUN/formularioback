<?php
include_once '../controllers/promociones.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	 $estado = $_POST['estado'];
	 $fechaRegistro = $_POST['fechaRegistro'];
	 $tipoPromocion = $_POST['tipoPromocion'];
	 $periodo = $_POST['periodo'];
	 $periodoIdiomas = $_POST['periodoIdiomas'];
	 $programa = $_POST['programa'];
	 $ciclo = $_POST['ciclo'];
	 $tipoInscripcion = $_POST['tipoInscripcion'];
	 $valorMatricula = $_POST['valorMatricula'];
	 $valorIdioma = $_POST['valorIdioma'];
	 $valorServicio = $_POST['valorServicio'];
	 $cuotas = $_POST['cuotas'];
	 $porcMatricula = $_POST['porcMatricula'];
	 $porcIdiomas = $_POST['porcIdiomas'];
	 $cunvive = $_POST['cunvive'];
	 $C_2X1 = $_POST['C_2X1'];
	  
	//Validar si ya existe el registro

	$exist = ParametrosController::ExistPromocion($db_conn,$tipoPromocion,$periodo,$periodoIdiomas,$programa,$ciclo,$tipoInscripcion);	

	if($exist == 1){
		echo 2;
	}else{	

		$id = ParametrosController::Selecsecuencia($db_conn);
	
		$result = ParametrosController::InsertPromocion($db_conn,$estado,$fechaRegistro,$tipoPromocion,$periodo,$periodoIdiomas,
		$programa,$ciclo,$tipoInscripcion,$valorMatricula,$valorIdioma,$valorServicio,$cuotas,$porcMatricula,$porcIdiomas,$id,$cunvive,$C_2X1);

		echo json_encode($result);

	}

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
