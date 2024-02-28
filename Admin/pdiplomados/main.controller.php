<?php

class Diplomados{


    public static function getCentroCostos($db){
		$response = [];

		$query = "SELECT * FROM centro_costo WHERE centro_costo_predecesor='714' AND nombre_centro_costo LIKE '%DIPLOMADO%'
        ORDER BY centro_costo ASC";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	while($x = oci_fetch_array($select,OCI_BOTH)){
    		$data = [
    				 'CENTRO_COSTO' => $x['CENTRO_COSTO']
                    ];

    		array_push($response, $data);		 
    	}

    	return $response;
	}

    
    public static function getProgramas($db,$regional,$periodo){
		$response = [];

		$query = "SELECT DISTINCT COD_CEN_COSTO,COD_UNIDAD,NOM_UNIDAD FROM cunv_diplomados 
        WHERE COD_SEDE='$regional' AND cod_periodo='$periodo'
        ORDER BY NOM_UNIDAD ASC";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	while($x = oci_fetch_array($select,OCI_BOTH)){
    		$data = [
    				 'COD_UNIDAD' => $x['COD_UNIDAD'],
                     'NOM_UNIDAD' => $x['NOM_UNIDAD'],
                     'CENTRO_COSTOS' => $x['COD_CEN_COSTO']
                    ];

    		array_push($response, $data);		 
    	}

    	return $response;
	}

    public static function getGrupos($db,$regional,$periodo,$programa){
		$response = [];

		$query = "SELECT DISTINCT NUM_GRUPO FROM cunv_diplomados WHERE COD_SEDE='$regional' 
        AND cod_periodo='$periodo' AND COD_UNIDAD='$programa'
        ORDER BY NUM_GRUPO ASC";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	while($x = oci_fetch_array($select,OCI_BOTH)){
    		$data = [
    				 'NUM_GRUPO' => $x['NUM_GRUPO']
                    ];

    		array_push($response, $data);		 
    	}

    	return $response;
	}

    public static function getLineaCredito($db){
        $response = [];

		$query = "SELECT LINEA_CREDITO, DESCRIPCION FROM LINEA_CREDITO WHERE LINEA_CREDITO IN(11, 12)";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	while($x = oci_fetch_array($select,OCI_BOTH)){
    		$data = [
    				 'LINEA_CREDITO' => $x['LINEA_CREDITO'],
    				 'DESCRIPCION' => $x['DESCRIPCION']
                    ];

    		array_push($response, $data);		 
    	}

    	return $response;
    }

    public static function getEncabezados($db,$periodo,$grupo,$centro_costos,$programa){


        $query = "SELECT
        CUN_PARAM_CREDITO_AUTOMATICO.SECUENCIA,
        cunv_periodos_activos.PERIODO,
        src_grupo.NUM_GRUPO,
        centro_costo.CENTRO_COSTO,
        src_uni_academica.NOM_UNIDAD,
        src_uni_academica.COD_UNIDAD
        FROM CUN_PARAM_CREDITO_AUTOMATICO
        LEFT JOIN cunv_periodos_activos ON CUN_PARAM_CREDITO_AUTOMATICO.PERIODO = cunv_periodos_activos.PERIODO
        LEFT JOIN src_grupo on CUN_PARAM_CREDITO_AUTOMATICO.GRUPO = src_grupo.NUM_GRUPO
        LEFT JOIN centro_costo on CUN_PARAM_CREDITO_AUTOMATICO.CENTRO_COSTO = centro_costo.CENTRO_COSTO
        LEFT JOIN src_uni_academica on CUN_PARAM_CREDITO_AUTOMATICO.COD_PROGRAMA = src_uni_academica.COD_UNIDAD
        WHERE CUN_PARAM_CREDITO_AUTOMATICO.PERIODO = '$periodo' AND CUN_PARAM_CREDITO_AUTOMATICO.GRUPO = '$grupo' 
        AND CUN_PARAM_CREDITO_AUTOMATICO.CENTRO_COSTO = '$centro_costos' AND CUN_PARAM_CREDITO_AUTOMATICO.COD_PROGRAMA = '$programa'
        GROUP BY CUN_PARAM_CREDITO_AUTOMATICO.SECUENCIA,
        cunv_periodos_activos.PERIODO,
        src_grupo.NUM_GRUPO,
        centro_costo.CENTRO_COSTO,
        src_uni_academica.NOM_UNIDAD,
        src_uni_academica.COD_UNIDAD
        ORDER BY CUN_PARAM_CREDITO_AUTOMATICO.SECUENCIA DESC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

    }
    
    
    
    public static function getSecuencia($db){


        $query = "SELECT MAX(SECUENCIA) SECUENCIA FROM CUN_PARAM_CREDITO_AUTOMATICO";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$secuencia = '';

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $secuencia = $row['SECUENCIA'];
		}

        if($secuencia == '' || $secuencia == null || empty($secuencia)){
            $secuencia = 1;
        }

        $secuencia_fin = $secuencia + 1;
        
		return $secuencia_fin;

    }
    

    public static function InsertEncabezado($db,$periodo,$grupo,$centro_costos,$programa,$valor_uso,$linea_credito){

        $query = "INSERT INTO CUN_PARAM_CREDITO_AUTOMATICO (TIPO_USO,VALOR_USO,LINEA_CREDITO,PERIODO,CENTRO_COSTO,USO_FECHA,FECHA_APROBACION,GRUPO,COD_PROGRAMA) 
        VALUES ('P','$valor_uso','$linea_credito','$periodo','$centro_costos','P',NULL,'$grupo','$programa')";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function getDetalleEncabezado($db,$secuencia){


        $query = "SELECT * FROM CUN_DETPAR_CREDITO_AUTOMATICO WHERE SECUENCIA = '$secuencia' ORDER BY NUMERO DESC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

    }


    public static function getTotRegistrosDetalle($db,$secuencia){


        $query = "SELECT COUNT(*) AS TOT FROM CUN_DETPAR_CREDITO_AUTOMATICO WHERE SECUENCIA = '$secuencia'
        ORDER BY FECHA_VENCIMIENTO DESC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$tot_reg = 0;

    	while($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $tot_reg = $row['TOT'];
		}

		return $tot_reg;

    }

    public static function InsertDetalle($db,$secuencia,$cuota,$fecha_vencimiento,$valor_uso){

        $query = "INSERT INTO CUN_DETPAR_CREDITO_AUTOMATICO
        (SECUENCIA, NUMERO, FECHA_VENCIMIENTO, VALOR_USO)
        VALUES ('$secuencia', '$cuota', TO_DATE('$fecha_vencimiento 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '$valor_uso')";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }
    
    public static function UpdateDetalle($db,$consecutivo,$valor_uso){

        $query = "UPDATE CUN_DETPAR_CREDITO_AUTOMATICO SET VALOR_USO = '$valor_uso' WHERE CONSECUTIVO = '$consecutivo'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }
    
    public static function DeleteDetalle($db,$secuencia_cab,$id_detalle){

        $query = "DELETE FROM CUN_DETPAR_CREDITO_AUTOMATICO WHERE SECUENCIA = '$secuencia_cab' AND CONSECUTIVO = '$id_detalle'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }
    

    public static function getDetalleEncabezadoDelete($db,$secuencia){


        $query = "SELECT * FROM CUN_DETPAR_CREDITO_AUTOMATICO WHERE SECUENCIA = '$secuencia' ORDER BY NUMERO ASC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function UpdateDetalleDelete($db,$cuota_new,$consecutivo,$valor_uso){

        $query = "UPDATE CUN_DETPAR_CREDITO_AUTOMATICO SET NUMERO = '$cuota_new', VALOR_USO = '$valor_uso' WHERE CONSECUTIVO = '$consecutivo'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function UpdateDetalleFecha($db,$id_detalle,$fecha_vencimiento){

        $query = "UPDATE CUN_DETPAR_CREDITO_AUTOMATICO SET FECHA_VENCIMIENTO = TO_DATE('$fecha_vencimiento 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        WHERE CONSECUTIVO = '$id_detalle'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function EditEncabezado($db,$secuencia,$periodo,$grupo,$centro_costos,$programa){

        $query = "UPDATE CUN_PARAM_CREDITO_AUTOMATICO SET PERIODO = '$periodo', CENTRO_COSTO = '$centro_costos', GRUPO = '$grupo', 
        COD_PROGRAMA = '$programa' WHERE SECUENCIA = '$secuencia'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }
    
    public static function getFechaMaxEncabezado($db,$secuencia){


        $query = "SELECT MAX(FECHA_VENCIMIENTO) AS FECHA_MAX FROM CUN_DETPAR_CREDITO_AUTOMATICO WHERE SECUENCIA = '$secuencia'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$fecha_max = '';

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $fecha_max = $row['FECHA_MAX'];
		}

		return $fecha_max;

    }
    
    public static function getSecuenciaInsert($db){


        $query = "SELECT MAX(SECUENCIA) SECUENCIA FROM CUN_PARAM_CREDITO_AUTOMATICO";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$secuencia = '';

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $secuencia = $row['SECUENCIA'];
		}

		return $secuencia;

    }  

    public static function getPeriodos($db){
		$response = [];

		$query = "SELECT DISTINCT cod_periodo FROM cunv_diplomados";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	while($x = oci_fetch_array($select,OCI_BOTH)){
    		$data = [
    				 'COD_PERIODO' => $x['COD_PERIODO']
                    ];

    		array_push($response, $data);		 
    	}

    	return $response;
	}
    
    public static function getRegionales($db,$periodo){
		$response = [];

		$query = "SELECT DISTINCT COD_SEDE,NOM_SEDE FROM cunv_diplomados WHERE cod_periodo='$periodo'
        ORDER BY NOM_SEDE ASC";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	while($x = oci_fetch_array($select,OCI_BOTH)){
    		$data = [
    				 'COD_REGIONAL' => $x['COD_SEDE'],
                     'NOM_REGIONAL' => $x['NOM_SEDE'],
                    ];

    		array_push($response, $data);		 
    	}

    	return $response;
	}

}