<?php
include_once 'main.controller.php';
//include '../../libs/configAdmin.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $registros = Credyty::getLoadsFiles($db_conn);

    echo json_encode($registros);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
