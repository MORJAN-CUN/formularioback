<?php
include_once 'controllers/main.controller.php';
//include '../libs/configAdmin.php';
include '../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$nom_rol = $_POST['nom_rol'];
	$est_rol = $_POST['est_rol'];
	$accesos = $_POST['accesos'];

	$result = AdminController::InsertPerfil($db_conn,$nom_rol,$est_rol,$accesos);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
