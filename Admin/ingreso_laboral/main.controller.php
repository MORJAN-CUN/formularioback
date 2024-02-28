<?php

class IngresoLaboral{

    public static function getEmpleados($db,$centro_costo,$estado,$palabra_clave){

		$datos_val = array(
			'centro_costo' => $centro_costo,
			'estado' => $estado,
			'palabra_clave' => $palabra_clave
		);

		//Recorrer para identificar cuales estan vacias

		$result_cadena = array();

		$query = "WHERE";
		array_push($result_cadena, $query);
		$query ="A.COD_EMPR='3031'";
		array_push($result_cadena, $query);
		$query = "AND";
		array_push($result_cadena, $query);
		$query = "PERFIL='ADMINISTRATIVO'";
		array_push($result_cadena, $query);

		foreach ($datos_val as $key => $valor){
		
			if($key == 'centro_costo'){
				if($valor != ''){
					//Validar si existe where ya
					if (in_array("WHERE", $result_cadena)) {
						//Agregar sentencia sin where
						$query = "AND";
						$query_str = "nc.cod_ccos = '$centro_costo'";
						array_push($result_cadena, $query);
						array_push($result_cadena, $query_str);
					}else{
						//Agregar sentencia y where
						$query = "WHERE";
						$query_str = "nc.cod_ccos = '$centro_costo'";
						array_push($result_cadena, $query);
						array_push($result_cadena, $query_str);
					}

				}
			}else if($key == 'estado'){
				if($valor != ''){
					//Validar si existe where ya
					if(in_array("WHERE", $result_cadena)){
						//Agregar sentencia sin where y con AND
						$query = "AND";
						$query_str = "DECODE (NC.IND_ACTI,'A','ACTIVO','RETIRADO') = '$estado'";
						array_push($result_cadena, $query);
						array_push($result_cadena, $query_str);
					}else{
						//Agregar sentencia con where y sin AND
						$query = "WHERE";
						$query_str = "DECODE (NC.IND_ACTI,'A','ACTIVO','RETIRADO') = '$estado'";
						array_push($result_cadena, $query);
						array_push($result_cadena, $query_str);
					}
				}

			}else if($key == 'palabra_clave'){
				if($valor != ''){
					//Validar si existe where ya
					if(in_array("WHERE", $result_cadena)){
						//Agregar sentencia sin where y con AND
						$query = "AND";
						$query_str = "(A.COD_EMPL LIKE '%$palabra_clave%') OR (A.ape_empl LIKE '%$palabra_clave%') OR (A.nom_empl LIKE '%$palabra_clave%')";
						$query2 = "OR";
						$query_str2 = "(A.box_mail LIKE '%$palabra_clave%')OR (B.dir_email LIKE '%$palabra_clave%')OR (c.dir_email LIKE '%$palabra_clave%')";
						array_push($result_cadena, $query);
						array_push($result_cadena, $query_str);
						array_push($result_cadena, $query2);
						array_push($result_cadena, $query_str2);
					}else{
						//Agregar sentencia con where y sin AND
						$query = "WHERE";
						$query_str = "(A.COD_EMPL LIKE '%$palabra_clave%') OR (A.ape_empl LIKE '%$palabra_clave%') OR (A.nom_empl LIKE '%$palabra_clave%')";
						$query2 = "OR";
						$query_str2 = "(A.box_mail LIKE '%$palabra_clave%')OR (B.dir_email LIKE '%$palabra_clave%')OR (c.dir_email LIKE '%$palabra_clave%')";
						array_push($result_cadena, $query);
						array_push($result_cadena, $query_str);
						array_push($result_cadena, $query2);
						array_push($result_cadena, $query_str2);
					}
				}
			}

		}

		$str = implode(' ', $result_cadena);

        $query = "SELECT  
		A.cod_empl
		,MAX(NC.NRO_CONT)NRO_CONT
		,A.ape_empl
		,A.nom_empl
		,A.tip_docu
		,DECODE (NC.IND_ACTI,'A','ACTIVO','RETIRADO') ESTADO
		,nc.cod_ccos 
		,d.nombre_centro_costo
		,A.box_mail CORREO_KACTUS
		,B.dir_email CORREO_ICEBERG
		,c.dir_email CORREO_IDAP
		FROM BI_EMPLE@SIGKACTUS A
		INNER JOIN NM_CONTR@SIGKACTUS NC ON A.COD_EMPL=NC.COD_EMPL AND  A.COD_EMPR=NC.COD_EMPR
		LEFT JOIN bas_tercero B ON to_char(A.cod_empl)=to_char(B.num_identificacion)
		LEFT JOIN cunt_ldap_correo_cun  C on to_char(c.num_identificacion)=to_char(a.cod_empl)
		left join centro_costo D ON TO_CHAR(NC.COD_CCOS)=TO_CHAR(D.CENTRO_COSTO)".$str."
		GROUP BY 
			A.cod_empl
			,A.ape_empl
			,A.nom_empl
			,A.tip_docu
			,NC.IND_ACTI
			,A.box_mail 
			,B.dir_email 
			,c.dir_email 
			,cod_ccos 
			,d.nombre_centro_costo
		ORDER BY A.nom_empl,A.ape_empl";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

    }

	public static function getCentroCostos($db){

		$query = "SELECT * FROM centro_costo 
		WHERE tipo_centro_costo='ADM' AND CENTRO_COSTO_PREDECESOR<>'ADM'
		ORDER BY NOMBRE_CENTRO_COSTO ASC";
		

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

	}


}