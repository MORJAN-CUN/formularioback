<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $periodo = $_POST['periodo'];
    $cedula = $_POST['cedula'];
    $nmro_cuenta = $_POST['nmro_cuenta'];

    $nomina = Nomina::DeducidosNivel1($db_conn,$periodo,$cedula,$nmro_cuenta);

    echo json_encode($nomina);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
