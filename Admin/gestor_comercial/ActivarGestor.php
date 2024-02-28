<?php
include_once 'main.controller.php';
include '../../libs/config.php';
set_time_limit(500);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $cedula = $_POST['cedula'];

    $cun_vive = Comerciales::ActivarGestor($db_conn,$cedula);
    $commit = Comerciales::commit($db_conn);

    echo $cun_vive;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
