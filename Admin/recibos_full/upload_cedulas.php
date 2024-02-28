<?php
include_once 'main.controller.php';
include '../../libs/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $data_entrada = $_POST;
    $registros  = $data_entrada['registros'];

    $reg_arr = json_decode($registros);


    foreach($reg_arr as $key){
        
        $cedula_estudiante = $key->cedula_estudiante;
        $periodo = $key->periodo;

        $select = RecibosFull::getData($db_conn,$cedula_estudiante,$periodo);

        echo json_encode($select);

    }


}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}
