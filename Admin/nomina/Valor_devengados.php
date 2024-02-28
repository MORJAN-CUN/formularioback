<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $periodo = $_POST['periodo'];
    $cedula = $_POST['cedula'];
    $nmro_cuenta = $_POST['nmro_cuenta'];
    $nivel1 = $_POST['nivel1'];
    $nivel2 = $_POST['nivel2'];
    $nivel3 = $_POST['nivel3'];

    $nomina = Nomina::ValorDevengados($db_conn,$periodo,$cedula,$nmro_cuenta,$nivel1,$nivel2,$nivel3);

    echo json_encode($nomina);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
