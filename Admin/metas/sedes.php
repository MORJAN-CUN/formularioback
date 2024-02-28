<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $cod_regional = $_POST['regional'];

	$result = Metas::getSedes($db_conn,$cod_regional);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}