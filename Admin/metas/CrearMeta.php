<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $unidad_negocio = $_POST['unidad_negocio'];
    $periodo = $_POST['periodo'];
    $tipo_ingreso = $_POST['tipo_ingreso'];
    $clase_ingreso = $_POST['clase_ingreso'];
    $regional = $_POST['regional'];
    $sede = $_POST['sede'];
    $programa = $_POST['programa'];
    $modalidad = $_POST['modalidad'];
    $ciclo = $_POST['ciclo'];
    $nivel_formacion = $_POST['nivel_formacion'];
    $tipo_alumno = $_POST['tipo_alumno'];
    $grupo = $_POST['grupo'];
    $valor_ingresos = $_POST['valor_ingresos'];
    $meta_estudiantes = $_POST['meta_estudiantes'];

    //Validar si ya existe

    $existe = Metas::ExisteMeta($db_conn,$unidad_negocio,$periodo,$tipo_ingreso,$clase_ingreso,$regional,
    $sede,$programa,$modalidad,$ciclo,$nivel_formacion,$tipo_alumno,$grupo,$valor_ingresos,$meta_estudiantes);

    if($existe == 1){

        $result = Metas::InsertMeta($db_conn,$unidad_negocio,$periodo,$tipo_ingreso,$clase_ingreso,$regional,$sede,$programa,$modalidad,$ciclo,$nivel_formacion,$tipo_alumno,$grupo,$valor_ingresos,$meta_estudiantes);

        //Guardar auditoria
    
        //Consultar id de meta insertada
    
        $id_meta = Metas::getIdMeta($db_conn);
    
        $id_usuario = $_POST['id_usu'];
        $cedula = $_POST['cedula'];
        $modulo = 'CM';
        $tipo = 'INSERCION';
    
        $descripcion = 'META: '.$id_meta.'    Se creo la nueva meta con los siguientes valores:
        unidad negocio: '.$unidad_negocio.'
        periodo: '.$periodo.'
        tipo ingreso: '.$tipo_ingreso.'
        clase ingreso: '.$clase_ingreso.'
        regional: '.$regional.'
        sede: '.$sede.'
        programa: '.$programa.'
        modalidad: '.$modalidad.'
        ciclo: '.$ciclo.'
        nivel formacion: '.$nivel_formacion.'
        tipo alumno: '.$tipo_alumno.'
        grupo: '.$grupo.'
        valor ingresos: '.$valor_ingresos.'
        meta estudiantes: '.$meta_estudiantes.'
        ';
    
        $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);
    
        echo $result;

    }else{
        echo 2;
    }

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}