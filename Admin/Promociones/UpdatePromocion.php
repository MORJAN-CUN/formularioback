<?php
include_once '../controllers/promociones.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$id_promocion = $_POST['id_promocion'];
	$estado = $_POST['estado'];
	$fecha_registro = $_POST['fecha_registro'];
	$tipo_promocion = $_POST['tipo_promocion'];
	$periodo = $_POST['periodo'];
	$periodo_idiomas = $_POST['periodo_idiomas'];
	$programa = $_POST['programa']; 
	$ciclo = $_POST['ciclo'];
	$tipo_inscripcion = $_POST['tipo_inscripcion'];
	$numero_cuotas = $_POST['numero_cuotas'];
	$valor_matricula = $_POST['valor_matricula'];
	$valor_idiomas = $_POST['valor_idiomas'];
	$valor_servicio = $_POST['valor_servicio'];
	$porc_matricula = $_POST['porc_matricula'];
	$porc_idiomas = $_POST['porc_idiomas'];
	$es_cun_vive = $_POST['es_cun_vive'];
	$es_2x1 = $_POST['es_2x1'];


	$tipoPromocionedit_antes = $_POST['tipoPromocionedit_antes'];
	$periodoedit_antes = $_POST['periodoedit_antes'];
	$periodoIdiomasedit_antes = $_POST['periodoIdiomasedit_antes'];
	$programaedit_antes = $_POST['programaedit_antes'];
	$cicloedit_antes = $_POST['cicloedit_antes'];
	$tipoInscripcionedit_antes = $_POST['tipoInscripcionedit_antes'];


	if($tipoPromocionedit_antes == $tipo_promocion && $periodoedit_antes == $periodo && $periodoIdiomasedit_antes == $periodo_idiomas
		&& $programaedit_antes == $programa && $cicloedit_antes == $ciclo && $tipoInscripcionedit_antes == $tipo_inscripcion){


		//Edit sin los primeros campos

		$tipoupd = 1;

		$result = ParametrosController::UpdatePromocion($db_conn,$id_promocion,$estado,$fecha_registro,$tipo_promocion,$periodo,$periodo_idiomas,$programa,$ciclo,
			$tipo_inscripcion,$numero_cuotas,$valor_matricula,$valor_idiomas,$valor_servicio,$porc_matricula,$porc_idiomas,$es_cun_vive,$es_2x1,$tipoupd);

	}else{	


			$exist = ParametrosController::ExistPromocionEdit($db_conn,$tipo_promocion,$periodo,$periodo_idiomas,$programa,$ciclo,$tipo_inscripcion);

		if($exist == 1){
		
		$result = 2;
	
		}else{	
			
			$tipoupd = 2;

			$result = ParametrosController::UpdatePromocion($db_conn,$id_promocion,$estado,$fecha_registro,$tipo_promocion,$periodo,$periodo_idiomas,$programa,$ciclo,
			$tipo_inscripcion,$numero_cuotas,$valor_matricula,$valor_idiomas,$valor_servicio,$porc_matricula,$porc_idiomas,$es_cun_vive,$es_2x1,$tipoupd);

		}


	}

	echo $result;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}