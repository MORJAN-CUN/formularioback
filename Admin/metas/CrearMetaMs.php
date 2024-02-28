<?php
include_once 'main.controller.php';
include '../../libs/config.php';
include '../auditoria/auditoria.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $unidad_negocio = $_POST['unidad_negocio'];
    $periodo = $_POST['periodo'];
    $grupo = $_POST['grupo_analisis'];
    $registros = $_POST['registros'];

    $reg_arr = json_decode($registros);
    $result_gen = array();

    $id_usuario = $_POST['id_usu'];
    $cedula = $_POST['cedula'];
    $modulo = 'CM';
    $tipo = 'INSERCION';

    foreach($reg_arr as $reg_new){
        
        //Obtener los datos
        $id = $reg_new[1];

        if($id == '' || $id == null || empty($id)){

        }else{

            $regional_txt = $reg_new[4];
            $regional_txt = trim($regional_txt);

            if($regional_txt == '' || $regional_txt == null || empty($regional_txt)){
                $regional = null;
            }else{
                $regional = Metas::getIdRegional($db_conn,$regional_txt);
            }

            $sede_txt = $reg_new[5];
            $sede_txt = trim($sede_txt);

            if($sede_txt == '' || $sede_txt == null || empty($sede_txt)){
                $sede = null;
            }else{
                $sede = Metas::getIdSede($db_conn,$sede_txt);
            }

            $tipo_alumno_txt = $reg_new[6];
            $tipo_alumno_txt = trim($tipo_alumno_txt);

            if($tipo_alumno_txt == '' || $tipo_alumno_txt == null || empty($tipo_alumno_txt)){
                $tipo_alumno = null;
            }else{
                $tipo_alumno = Metas::getIdTipoAlumno($db_conn,$tipo_alumno_txt);
            }

            $programa_txt = $reg_new[7];
            $programa_txt = trim($programa_txt);

            if($programa_txt == '' || $programa_txt == null || empty($programa_txt)){
                $programa = null;
            }else{
                $programa = Metas::getIdPrograma($db_conn,$programa_txt);
            }

            $modalidad_txt = $reg_new[8];
            $modalidad_txt = trim($modalidad_txt);

            if($modalidad_txt == '' || $modalidad_txt == null || empty($modalidad_txt)){
                $modalidad = null;
            }else{
                $modalidad = Metas::getIdModalidad($db_conn,$modalidad_txt);
            }

            $ciclo_txt = $reg_new[9];
            $ciclo_txt = trim($ciclo_txt);

            if($ciclo_txt == '' || $ciclo_txt == null || empty($ciclo_txt)){
                $ciclo = null;
            }else{
                $ciclo = Metas::getIdCiclo($db_conn,$ciclo_txt);
            }

            $meta_estudiantes = $reg_new[10];
            $valor_ingresos = $reg_new[11];

            $valor_ingresos = str_replace('$','',$valor_ingresos);
            $valor_ingresos = str_replace(',','',$valor_ingresos);

            $tipo_ingreso = null;
            $clase_ingreso = null;
            $nivel_formacion = null;

            $unidad_negocio = trim($unidad_negocio);
            $periodo = trim($periodo);
            $tipo_ingreso = trim($tipo_ingreso);
            $clase_ingreso = trim($clase_ingreso);
            $regional = trim($regional);
            $sede = trim($sede);
            $programa = trim($programa);
            $modalidad = trim($modalidad);
            $ciclo = trim($ciclo);
            $nivel_formacion = trim($nivel_formacion);
            $tipo_alumno = trim($tipo_alumno);
            $grupo = trim($grupo);
            $valor_ingresos = trim($valor_ingresos);
            $meta_estudiantes = trim($meta_estudiantes);

            //Validar si ya existe

            $existe = Metas::ExisteMeta($db_conn,$unidad_negocio,$periodo,$tipo_ingreso,$clase_ingreso,$regional,
            $sede,$programa,$modalidad,$ciclo,$nivel_formacion,$tipo_alumno,$grupo,$valor_ingresos,$meta_estudiantes);

            if($existe == 1){

                $insert_reg = Metas::InsertMeta($db_conn,$unidad_negocio,$periodo,$tipo_ingreso,$clase_ingreso,$regional,$sede,$programa,$modalidad,$ciclo,
                $nivel_formacion,$tipo_alumno,$grupo,$valor_ingresos,$meta_estudiantes);

                if($insert_reg == 1){

                    $id_meta = Metas::getIdMeta($db_conn);

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

                    $array_r = array(
                        'id' => $id_meta,
                        'status' => 1,
                        'message' => 'Creado correctamente, con el periodo: '.$periodo.' y el grupo: '.$grupo
                    );

                }else{

                    $array_r = array(
                        'id' => '',
                        'status' => 0,
                        'message' => 'Error desconocido al insertar en la base de datos: '.$periodo.' y el grupo: '.$grupo
                    );

                }

                

            }else{

                $array_r = array(
                    'id' => $id,
                    'status' => 0,
                    'message' => 'Error al insertar, ya existe el registro: '.$periodo.' y el grupo: '.$grupo
                );

            }

            array_push($result_gen, $array_r);

        }

    }

	echo json_encode($result_gen);


}else{
	
	$error = ['error'=> '405 Method Not Allowed'];
	echo json_encode($error);
}