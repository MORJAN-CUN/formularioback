<?php
include_once 'main.controller.php';
include '../../libs/config.php';
set_time_limit(500); // 

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    /* $nombre = $_POST['nombre']; */
    /* $cedula  = $_POST['cedula']; */

    $comerciales = Comerciales::getComerciales($db_conn);
    echo json_encode($comerciales);

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
