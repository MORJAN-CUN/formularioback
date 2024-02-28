<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	$regional = $_POST['regional'];
	$periodo = $_POST['periodo'];
	$programa = $_POST['programa'];

	$result = Diplomados::getGrupos($db_conn,$regional,$periodo,$programa);

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}