<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id = $_POST['id'];
    $regional = $_POST['regional'];
    $sede = $_POST['sede'];
    $programa = $_POST['programa'];
    $modalidad = $_POST['modalidad'];
    $ciclo = $_POST['ciclo'];
    $tipo_alumno = $_POST['tipo_alumno'];
    $valor_ingresos = $_POST['valor_ingresos'];
    $meta_estudiantes = $_POST['meta_estudiantes'];

	$result = Metas::UpdateMetaReg($db_conn,$id,$regional,$sede,$programa,$modalidad,$ciclo,$tipo_alumno,$valor_ingresos,$meta_estudiantes);

    //Auditoria

    $id_usuario = $_POST['id_usu'];
    $cedula = $_POST['cedula'];

    $regional_edit_audi = $_POST['regional_edit_audi'];
    $sede_edit_audi = $_POST['sede_edit_audi'];
    $tipo_alumno_edit_audi = $_POST['tipo_alumno_edit_audi'];
    $programa_edit_audi = $_POST['programa_edit_audi'];
    $modalidad_edit_audi = $_POST['modalidad_edit_audi'];
    $ciclo_edit_audi = $_POST['ciclo_edit_audi'];
    $meta_estudiantes_edit_audi = $_POST['meta_estudiantes_edit_audi'];
    $valor_ingresos_edit_audi = $_POST['valor_ingresos_edit_audi'];

    $modulo = 'CM';
    $tipo = 'ACTUALIZACION';

    $descripcion = 'META: '.$id.'    Se actualizo la meta con los siguientes cambios:
    regional: '.$regional_edit_audi.' regional nuevo: '.$regional.'
    sede: '.$sede_edit_audi.' sede nuevo: '.$sede.'
    programa: '.$programa_edit_audi.' programa nuevo: '.$programa.'
    modalidad: '.$modalidad_edit_audi.' modalidad nuevo: '.$modalidad.'
    ciclo: '.$ciclo_edit_audi.' ciclo nuevo: '.$ciclo.'
    tipo alumno: '.$tipo_alumno_edit_audi.' tipo alumno nuevo: '.$tipo_alumno.'
    valor ingresos: '.$valor_ingresos_edit_audi.' valor ingresos nuevo: '.$valor_ingresos.'
    meta estudiantes: '.$meta_estudiantes_edit_audi.' meta estudiantes nuevo: '.$meta_estudiantes.'
    ';

    $auditoria = InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion);

	echo $result;

}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}