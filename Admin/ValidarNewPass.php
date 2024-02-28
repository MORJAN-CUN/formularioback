<?php
include_once 'controllers/main.controller.php';
//include '../../libs/configAdmin.php';
include '../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$email = $_POST['email'];
	$cedula = $_POST['cedula'];

	$result = AdminController::getNewPass($db_conn,$email,$cedula);
    
	if($result == 1){
        //Enviar email
        echo 1;
    }else{
        echo 0;
    }


}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
