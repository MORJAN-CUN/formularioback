<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $periodo = $_POST['periodo'];

	$sin_ingreso = Correo::SinIngreso($db_conn,$periodo);
    $ya_ingresaron = Correo::YaIngresaron($db_conn,$periodo);
    $ingreso_desconocido = Correo::IngresoDesconocido($db_conn,$periodo);
    $total_ingresos = $sin_ingreso + $ya_ingresaron + $ingreso_desconocido;

    $result = array(
        'sin_ingreso' => $sin_ingreso,
        'ya_ingresaron' => $ya_ingresaron,
        'ingreso_desconocido' => $ingreso_desconocido,
        'total_ingresos' => $total_ingresos
    );

	echo json_encode($result);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}