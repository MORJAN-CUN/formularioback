<?php
include_once 'controllers/main.controller.php';
//include '../../libs/configAdmin.php';
include '../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$user = $_POST['user'];
	$pass = $_POST['pass'];

	$result = AdminController::getLogin($db_conn,$user,$pass);

	$id = $result['id'];

	if($id == '' || $id == null || empty($id)){
		$status = 0;
	}else{
		$status = 1;
	}

	$resp = array(
		'status' => $status,
		'id_empl' => $result['id'],
		'perfil' => $result['perfil']
	);

	echo json_encode($resp);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
