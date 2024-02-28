<?php

class Metas{

    public static function InsertTipoMeta($db,$nombre_meta,$clase_ingreso,$tipo_ingreso,$regional,$sede,$modalidad,$programa,$nivel,$ciclo,$tipo_alumno,$grupo,$valor_meta,$cantidad_meta){

        $query = "INSERT INTO CUNT_PARAMETROS_METAS (NOMBRE_META,CON_TINGRESO,CON_CINGRESO,CON_SECC,CON_SEDE,CON_PROGRAMA,CON_MODA,CON_CICLO,
        CON_NFORMA,CON_TALUMNO,CON_GRUPO,CON_VMETA,CON_CMETA,F_CREA) 
        VALUES('$nombre_meta','$tipo_ingreso','$clase_ingreso','$regional','$sede','$programa','$modalidad','$ciclo','$nivel',
        '$tipo_alumno','$grupo','$valor_meta','$cantidad_meta',SYSDATE)";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function GetTiposMetas($db){


        $query = "SELECT * FROM CUNT_PARAMETROS_METAS ORDER BY F_CREA DESC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function getDatosMeta($db,$id_meta){


        $query = "SELECT * FROM CUNT_PARAMETROS_METAS WHERE SECUENCIA = '$id_meta'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function UpdateUnidadNegocio($db,$id,$nombre_meta,$modalidad,$programa,$nivel,$ciclo,$tipo_alumno,$valor_ingresos,$cantidad_estudiantes){

        $query = "UPDATE CUNT_PARAMETROS_METAS SET NOMBRE_META = '$nombre_meta', CON_MODA = '$modalidad', 
        CON_PROGRAMA  = '$programa', CON_NFORMA  = '$nivel', CON_CICLO  = '$ciclo', 
        CON_TALUMNO = '$tipo_alumno', CON_VMETA = '$valor_ingresos', CON_CMETA = '$cantidad_estudiantes' WHERE SECUENCIA = '$id'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function getUnidadesNegocios($db){
		$response = [];

		$query = "SELECT SECUENCIA, NOMBRE_META from CUNT_PARAMETROS_METAS ORDER BY NOMBRE_META ASC";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	while($x = oci_fetch_array($select,OCI_BOTH)){
    		$data = ['id' => $x['SECUENCIA'],
    				 'nombre_meta' => $x['NOMBRE_META']
                    ];

    		array_push($response, $data);		 
    	}

    	return $response;
	}

    public static function getGruposAnalisis($db){


        $query = "SELECT ORDEN_GRUPO,COMPARA_GRUPO,INICIO,FINAL FROM ICEBERG.CUNV_GRUPO_ANALISIS ORDER BY ORDEN_GRUPO ASC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

    }
    
    public static function getRegionales($db){


        $query = "SELECT COD_SECC, SECCIONAL FROM ICEBERG.CUNV_REGIONALES ORDER BY SECCIONAL ASC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function getSedes($db,$cod_regional){


        $query = "SELECT COD_SEDE,SEDE,COD_REG FROM ICEBERG.CUNV_SEDES WHERE COD_REG = '$cod_regional'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }


    public static function getProgramas($db){


        $query = "SELECT COD_PROGRAMA,NOM_PROGRAMA FROM ICEBERG.CUNV_PROGRAMAS ORDER BY NOM_PROGRAMA ASC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function getTiposAlumnos($db){


        $query = "SELECT * FROM CUNT_AGRUPADOR_TALUM ORDER BY DESCRIPCION ASC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }
    
    public static function getModalidad($db){


        $query = "SELECT COD_MODA,MODALIDAD FROM ICEBERG.CUNV_MODALIDADES ORDER BY MODALIDAD ASC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function getCiclos($db){


        $query = "SELECT COD_CICLO,CICLO FROM ICEBERG.CUNV_CICLOS ORDER BY CICLO ASC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function InsertMeta($db,$unidad_negocio,$periodo,$tipo_ingreso,$clase_ingreso,$regional,$sede,$programa,$modalidad,$ciclo,$nivel_formacion,$tipo_alumno,$grupo,$valor_ingresos,$meta_estudiantes){

        $query = "INSERT INTO CUNT_DETALLE_META(PERIODO,TIPO_INGRESO,CLASE_INGRESO,COD_SECC,COD_SEDE,COD_PROGRAMA,COD_MODA,COD_CICLO,NIV_FOR,
        TIPO_ALUMNO,GRUPO,V_META,C_META_,SECUENCIA_CABECERA) 
        VALUES('$periodo','$tipo_ingreso','$clase_ingreso','$regional','$sede','$programa','$modalidad','$ciclo',
        '$nivel_formacion','$tipo_alumno','$grupo','$valor_ingresos','$meta_estudiantes','$unidad_negocio')";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }
    
    public static function getMetasCreadas($db,$unidad_negocio,$periodo,$grupo_analisis){

		if($periodo == null || $periodo == '' || empty($periodo)){
			$periodo = "";
		}else{
			$periodo = " AND PERIODO = '$periodo' ";
		}

		if($grupo_analisis == null || $grupo_analisis == '' || empty($grupo_analisis)){
			$grupo_analisis = "";
		}else{
			$grupo_analisis = " AND GRUPO = '$grupo_analisis' ";
		}

        $query = "SELECT
		CUNT_DETALLE_META.GRUPO,
		CUNT_DETALLE_META.PERIODO,
        CUNT_DETALLE_META.SECUENCIA_META AS ID,
        ICEBERG.CUNV_REGIONALES.SECCIONAL AS REGIONAL,
        ICEBERG.CUNV_SEDES.SEDE AS SEDE,
        CUNT_AGRUPADOR_TALUM.DESCRIPCION AS TIPO_ALUMNO,
        ICEBERG.CUNV_PROGRAMAS.NOM_PROGRAMA AS NOM_PROGRAMA,
        ICEBERG.CUNV_MODALIDADES.MODALIDAD AS MODALIDAD,
        ICEBERG.CUNV_CICLOS.CICLO AS CICLO,
        CUNT_DETALLE_META.C_META_ AS META_ESTUDIANTES,
        CUNT_DETALLE_META.V_META AS META_VALOR_INGRESOS
        FROM CUNT_DETALLE_META 
        LEFT JOIN ICEBERG.CUNV_REGIONALES ON CUNT_DETALLE_META.COD_SECC = ICEBERG.CUNV_REGIONALES.COD_SECC 
        LEFT JOIN ICEBERG.CUNV_SEDES ON CUNT_DETALLE_META.COD_SEDE = ICEBERG.CUNV_SEDES.COD_SEDE
        LEFT JOIN CUNT_AGRUPADOR_TALUM ON CUNT_DETALLE_META.TIPO_ALUMNO = CUNT_AGRUPADOR_TALUM.ID
        LEFT JOIN ICEBERG.CUNV_PROGRAMAS ON CUNT_DETALLE_META.COD_PROGRAMA = ICEBERG.CUNV_PROGRAMAS.COD_PROGRAMA
        LEFT JOIN ICEBERG.CUNV_MODALIDADES ON CUNT_DETALLE_META.COD_MODA = ICEBERG.CUNV_MODALIDADES.COD_MODA
        LEFT JOIN ICEBERG.CUNV_CICLOS ON CUNT_DETALLE_META.COD_CICLO = ICEBERG.CUNV_CICLOS.COD_CICLO 
        WHERE SECUENCIA_CABECERA = '$unidad_negocio'".$periodo.$grupo_analisis."
        ORDER BY GRUPO,REGIONAL,SEDE,TIPO_ALUMNO,NOM_PROGRAMA,MODALIDAD,CICLO ASC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function getDatosMetaReg($db,$id){

        $query = "SELECT * FROM CUNT_DETALLE_META WHERE SECUENCIA_META = '$id'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        array_push($result, $row);
		}

		return $result;

    }

    public static function UpdateMetaReg($db,$id,$regional,$sede,$programa,$modalidad,$ciclo,$tipo_alumno,$valor_ingresos,$meta_estudiantes){

        $query = "UPDATE CUNT_DETALLE_META SET COD_SECC = '$regional', COD_SEDE = '$sede', TIPO_ALUMNO = '$tipo_alumno', 
        COD_PROGRAMA = '$programa',  COD_MODA = '$modalidad', COD_CICLO  = '$ciclo', C_META_ = '$meta_estudiantes', V_META = '$valor_ingresos'
        WHERE SECUENCIA_META = '$id'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function getTotValues($db,$id,$periodo){

        $query = "SELECT SUM(C_META_) AS CANTIDAD, SUM(V_META) AS VALOR FROM CUNT_DETALLE_META 
        WHERE SECUENCIA_CABECERA = '$id' AND PERIODO = '$periodo'-- AND GRUPO = '$grupo_analisis'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        array_push($result, $row);
		}

		return $result;

    }

    public static function getIdTipoAlumno($db,$tipo_alumno){

        $query = "SELECT ID FROM CUNT_AGRUPADOR_TALUM WHERE DESCRIPCION LIKE '%$tipo_alumno%'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$id = '';

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $id = $row['ID'];
		}

		return $id;

    }
    
    public static function getIdPrograma($db,$programa){

        $query = "SELECT COD_PROGRAMA,NOM_PROGRAMA FROM ICEBERG.CUNV_PROGRAMAS WHERE NOM_PROGRAMA = '$programa'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$id = '';

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $id = $row['COD_PROGRAMA'];
		}

		return $id;

    }

    public static function getIdModalidad($db,$modalidad){

        $query = "SELECT COD_MODA,MODALIDAD FROM ICEBERG.CUNV_MODALIDADES WHERE MODALIDAD = '$modalidad'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$id = '';

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $id = $row['COD_MODA'];
		}

		return $id;

    }

    public static function getIdCiclo($db,$ciclo){

        $query = "SELECT COD_CICLO,CICLO FROM ICEBERG.CUNV_CICLOS WHERE CICLO = '$ciclo'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$id = '';

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $id = $row['COD_CICLO'];
		}

		return $id;

    }

    public static function getIdRegional($db,$regional){

        $query = "SELECT COD_SECC, SECCIONAL FROM ICEBERG.CUNV_REGIONALES WHERE SECCIONAL = '$regional'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$id = '';

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $id = $row['COD_SECC'];
		}

		return $id;

    }

    public static function getIdSede($db,$sede){

        $query = "SELECT COD_SEDE,SEDE,COD_REG FROM ICEBERG.CUNV_SEDES WHERE SEDE = '$sede'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$id = '';

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $id = $row['COD_SEDE'];
		}

		return $id;

    }
    
    public static function getIdMeta($db){

        $query = "SELECT MAX(SECUENCIA_META) SECUENCIA FROM CUNT_DETALLE_META";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$id = '';

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $id = $row['SECUENCIA'];
		}

		return $id;

    }
	
	public static function ExisteMeta($db,$unidad_negocio,$periodo,$tipo_ingreso,$clase_ingreso,$regional,$sede,
	$programa,$modalidad,$ciclo,$nivel_formacion,$tipo_alumno,$grupo,$valor_ingresos,$meta_estudiantes){

		//Validar null

		if($programa == '' || $programa == null || empty($programa)){
			$programa = " AND COD_PROGRAMA IS NULL";
		}else{
			$programa =" AND COD_PROGRAMA = '$programa'";
		}

		if($modalidad == '' || $modalidad == null || empty($modalidad)){
			$modalidad = " AND COD_MODA IS NULL";
		}else{
			$modalidad =" AND COD_MODA = '$modalidad'";
		}


		if($ciclo == '' || $ciclo == null || empty($ciclo)){
			$ciclo = " AND COD_CICLO IS NULL";
		}else{
			$ciclo =" AND COD_CICLO = '$ciclo'";
		}		

		$nivel_formacion= " AND NIV_FOR IS NULL";

		if($tipo_alumno == '' || $tipo_alumno == null || empty($tipo_alumno)){
			$tipo_alumno = " AND TIPO_ALUMNO IS NULL";
		}else{
			$tipo_alumno =" AND TIPO_ALUMNO = '$tipo_alumno'";
		}

		$grupo = " AND GRUPO = '$grupo'";

		if($valor_ingresos == '' || $valor_ingresos == null || empty($valor_ingresos)){
			$valor_ingresos = " AND V_META IS NULL";
		}else{
			$valor_ingresos =" AND V_META = '$valor_ingresos'";
		}

		if($meta_estudiantes == '' || $meta_estudiantes == null || empty($meta_estudiantes)){
			$meta_estudiantes = " AND C_META_ IS NULL";
		}else{
			$meta_estudiantes =" AND C_META_ = '$meta_estudiantes'";
		}

        $query = "SELECT * FROM CUNT_DETALLE_META WHERE 
		PERIODO  = '$periodo' AND 
		TIPO_INGRESO IS NULL AND 
		CLASE_INGRESO IS NULL AND 
		COD_SECC = '$regional' AND 
		COD_SEDE = '$sede'".$programa.$modalidad.$ciclo.$nivel_formacion.$tipo_alumno.$grupo.$valor_ingresos.$meta_estudiantes." AND 
		SECUENCIA_CABECERA = '$unidad_negocio'";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	if ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result = 0;
		}else{
			$result = 1;
		}

		return $result;

    }
	
	public static function getGruposAnalisisTot($db,$unidad_negocio,$periodo){

		if($periodo == '' || $periodo == null || empty($periodo)){
			$periodo = "";
		}else{
			$periodo = " AND dm.PERIODO = '$periodo' ";
		}

		$query = "SELECT dm.PERIODO, m.COMPARA_GRUPO GRUPO, m.INICIO,m.FINAL, COALESCE(SUM(dm.V_META), 0) TOTAL_META, COALESCE(SUM(dm.C_META_), 0) TOTAL_ESTUDIANTES
		FROM ICEBERG.CUNV_GRUPO_ANALISIS m
		LEFT JOIN CUNT_DETALLE_META dm ON dm.GRUPO = m.COMPARA_GRUPO
		WHERE dm.SECUENCIA_CABECERA = '$unidad_negocio'".$periodo."
		GROUP BY m.COMPARA_GRUPO,m.INICIO,m.FINAL,dm.PERIODO
		ORDER BY m.COMPARA_GRUPO ASC
		";

		$select = oci_parse($db, $query);
				
		oci_execute($select);

		$result = array();

		while ($row = oci_fetch_array($select, OCI_ASSOC)) {
			array_push($result, $row);
		}

		return $result;


	}

	public static function commit($db){

        $query = "COMMIT";
  
		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

	public static function deleteMeta($db,$id_meta){

		$query = "DELETE FROM CUNT_DETALLE_META WHERE SECUENCIA_META = '$id_meta'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }


	}

	public static function deleteMetasGrupo($db,$unidad_negocio,$grupo_analisis,$periodo){

		$query = "DELETE FROM CUNT_DETALLE_META WHERE SECUENCIA_CABECERA = '$unidad_negocio' AND PERIODO = '$periodo'
		AND GRUPO = '$grupo_analisis'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }


	}
	

}