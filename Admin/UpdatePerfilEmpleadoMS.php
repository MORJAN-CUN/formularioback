<?php
include_once 'controllers/main.controller.php';
//include '../libs/configAdmin.php';
include '../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$empleados_ids = $_POST['empleados_ids'];
	$perfil = $_POST['id_perfil'];

	//Convertir string en Array

	$arr_empleados = explode(",", $empleados_ids);

	foreach($arr_empleados as $key){

		$id = $key;
		$result = AdminController::UpdatePerfilEmpl($db_conn,$id,$perfil);

	}

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
