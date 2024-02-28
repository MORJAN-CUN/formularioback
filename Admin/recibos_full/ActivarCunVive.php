<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $periodo = $_POST['periodo'];
    $cedula = $_POST['cedula'];


    $cun_vive = RecibosFull::ActivarCunVive($db_conn,$periodo,$cedula);
    $commit = RecibosFull::commit($db_conn);

    echo $cun_vive;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
