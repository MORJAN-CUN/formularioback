<?php
include_once 'controllers/main.controller.php';
//include '../libs/configAdmin.php';
include '../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$id_user = $_POST['id_user'];
	$pass_actual = $_POST['pass_actual'];
	$pass_new = $_POST['pass_new'];

	//Validar si la pass actual es correcta

	$result = AdminController::getDataEmpleado($db_conn,$id_user);

	$pass_act_bd = $result['password'];

	if($pass_act_bd == $pass_actual){
		
		//Hacer update de contraseña

		$result = AdminController::UpdatePassword($db_conn,$id_user,$pass_new);

		echo $result;

	}else{
		echo 2;
	}


	//echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
