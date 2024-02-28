<?php
/**
 * 
 */

class ServiceController
{
	
	public function tipoDocumento($db){
		
		$query = "SELECT
					G.COD_TABLA,
					G.NOM_TABLA 
				  FROM
					SRC_GENERICA G 
				  WHERE G.TIP_TABLA = 'TIPIDE'
				  AND COD_SNIES <> ' '
				  AND COD_TABLA  NOT IN 'R'
				  AND COD_TABLA  NOT IN 'N'";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = ['tipo_documento' => $row['COD_TABLA'],
	        		 'nombre_tipo_documento' => $row['NOM_TABLA']
	                ];
        
        array_push($response,$data);

		}
		
		return $response;

	}

	
	public function regional($db){
		$query = "SELECT ID_SECCIONAL, NOM_SECCIONAL FROM sinu.src_seccional WHERE nom_seccional LIKE '%Regional%'";
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = ['id_regional' => $row['ID_SECCIONAL'],
	        		 'nombre_regional' => $row['NOM_SECCIONAL']
	                ];
        
        array_push($response,$data);
		}
		return $response;
	}

	
	public function promocion($db){

		$query = "SELECT DISTINCT TIPO_PROMOCION, ES_CUN_VIVE, ES_2X1 
					FROM CUNT_PROMOCIONES_DISPONIBLES
					WHERE ESTADO = 'A'";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = ['promocion' => $row['TIPO_PROMOCION'],
	        		 'cun_vive' => $row['ES_CUN_VIVE'],
	        		 '2x1' => $row['ES_2X1']
	                ];
        
        array_push($response,$data);
		}
		
		return $response;

	}

	public function promocion_disponible($db){

		$query = "SELECT DISTINCT TIPO_PROMOCION 
					FROM CUNT_PROMO_PARAMETRO_X_PERIODO
					WHERE ESTADO = 'A'";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = [
				'promocion' => $row['TIPO_PROMOCION']
			];
        
        array_push($response,$data);
		}
		
		return $response;

	}


	public function periodo($db, $promocion){
		
		$query = "SELECT DISTINCT PERIODO
					FROM CUNT_PROMOCIONES_DISPONIBLES
					WHERE ESTADO = 'A'
					AND TIPO_PROMOCION = 'PAGO ANTICIPADO'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = ['periodo' => $row['PERIODO'],
	        		 'periodo_idiomas' => (isset($row['PERIODO_IDIOMAS'])) ? $row['PERIODO_IDIOMAS'] : ''
	                ];
        
        array_push($response,$data);
		}
		return $response;
	}

	public function programa($db,$promocion,$periodo){

		$query = "SELECT DISTINCT PROGRAMA
					FROM CUNT_PROMOCIONES_DISPONIBLES
					WHERE ESTADO = 'A'
					AND TIPO_PROMOCION = '$promocion'
					AND PERIODO = '$periodo'";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = ['programa' => $row['PROGRAMA']
	                ];
        
        array_push($response,$data);
		}
		return $response;
	}

	public function ciclo($db, $promocion, $periodo, $programa){
		
		$query = "SELECT DISTINCT CICLO
					FROM CUNT_PROMOCIONES_DISPONIBLES
					WHERE ESTADO = 'A'
					AND TIPO_PROMOCION = '$promocion'
					AND PERIODO = '$periodo'
					AND PROGRAMA = '$programa'
					ORDER BY CICLO DESC";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = ['ciclo' => $row['CICLO']
	                ];
        
        array_push($response,$data);
		}
		return $response;
	}

	public function tipoFormacion($db, $promocion, $periodo, $programa, $ciclo){

		$query = "SELECT DISTINCT TIPO_INSCRIPCION
					FROM CUNT_PROMOCIONES_DISPONIBLES
					WHERE ESTADO = 'A'
					AND TIPO_PROMOCION = '$promocion'
					AND PERIODO = '$periodo'
					AND PROGRAMA = '$programa'
					AND CICLO = '$ciclo'";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = ['tipo_inscripcion' => $row['TIPO_INSCRIPCION']
	                ];
        
        array_push($response,$data);
		}
		return $response;
	}

	public function valorMatricula($db, $promocion, $periodo, $programa, $ciclo, $tipoFormacion){
		$query = "SELECT DISTINCT VALOR_MATRICULA, VALOR_IDIOMAS, VALOR_SERVICIO
					FROM CUNT_PROMOCIONES_DISPONIBLES
					WHERE ESTADO = 'A'
					AND TIPO_PROMOCION = '$promocion'
					AND PERIODO = '$periodo'
					AND PROGRAMA = '$programa'
					AND CICLO = '$ciclo'
					AND TIPO_INSCRIPCION = '$tipoFormacion'";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = ['valor_matricula' => $row['VALOR_MATRICULA'],
	        		 'valor_idiomas' => $row['VALOR_IDIOMAS'],
	        		 'valor_servicio' => $row['VALOR_SERVICIO']
	                ];
        
        array_push($response,$data);
		}
		return $response;
	}

	public function cuotas($db, $promocion, $periodo, $programa, $ciclo, $tipoFormacion, $valorMatricula){
		$query = "SELECT DISTINCT  NUMERO_CUOTAS
					FROM CUNT_PROMOCIONES_DISPONIBLES
					WHERE ESTADO = 'A'
					AND TIPO_PROMOCION = '$promocion'
					AND PERIODO = '$periodo'
					AND PROGRAMA = '$programa'
					AND CICLO = '$ciclo'
					AND TIPO_INSCRIPCION = '$tipoFormacion'
					AND VALOR_MATRICULA = '$valorMatricula'";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = ['cuotas' => $row['NUMERO_CUOTAS']
	                ];
        
        array_push($response,$data);
		}
		return $response;
	}

	public function descuentos($db, $periodo = '22V06'){
		$query = "SELECT 
		GRUPO,
		(PORCENTAJE * -1) PORCENTAJE
		FROM VENCIMIENTO_PERIODO 
		WHERE PERIODO = '$periodo'
		AND GRUPO > 100
		AND FECHA_VENCIMIENTO > SYSDATE
		ORDER BY PORCENTAJE";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $data = [
				'grupo' => $row['GRUPO'],
				'porcentaje' => $row['PORCENTAJE'],
	        ];
        
        array_push($response,$data);
		}
		return $response;
	}	

	public function registraPromocion($db, $info){

			$promocion = $info['promocion'];
			$periodo = $info['periodo'];
			$programa = $info['programa'];
			$ciclo = $info['ciclo'];
			$tipo_inscripcion = $info['tipo_formacion'];
			$documento = $info['documento'];
			$nombres = $info['primer_nombre'].' '.$info['segundo_nombre'];
			$p_nombre = $info['primer_nombre'];
			$s_nombre = $info['segundo_nombre'];
			$p_apellido = $info['primer_apellido']; 
			$s_apellido = $info['segundo_apellido'];
			$apellidos = $info['primer_apellido'].' '.$info['segundo_apellido'];
			$correo = $info['correo'];
			$telefono_fijo = $info['telefono_fijo'];
			$celular = $info['celular'];
			$cuotas = $info['cuotas'];
			$cubrimiento = $info['cubrimiento'];
			$tipoDocumento = $info['tipo_documento'];
			$genero = $info['genero'];
			$fecha_nacimiento = $info['fecha_nacimiento'];
			$fecha_expedicion = $info['fecha_expedicion'];
			$valor_matricula = $info['valor_matricula'];
			$usuario_id = $info['usuario_id'];
			$grupo_facturacion = $info['valor_descuento'];

			// Turn dbms_output ON or OFF
			function SetServerOutput($db, $p)
			{
			    if ($p)
			      $s = "BEGIN DBMS_OUTPUT.ENABLE(NULL); END;";
			    else
			      $s = "BEGIN DBMS_OUTPUT.DISABLE(); END;";
			    
			    $s = oci_parse($db, $s);
			    $r = oci_execute($s);
			    oci_free_statement($s);
			    return $r;
			}

			// Returns an array of dbms_output lines, or false.
			function GetDbmsOutput($db)
			{	
				$res = false;
			    $s = oci_parse($db, "BEGIN DBMS_OUTPUT.GET_LINE(:LN, :ST); END;");

			    if (oci_bind_by_name($s, ":LN", $ln, 255) && oci_bind_by_name($s, ":ST", $st)) {
			    $res = array();
			    while (($succ = oci_execute($s)) && !$st)
			      $res[] = $ln;
			        if (!$succ)
			         $res = false;
			        }
			    oci_free_statement($s);
			    return $res;
			}

			// Turn on buffering of output //
			SetServerOutput($db, true);

		if(count($info) == 22){

			//crea la promocion
			$query = "DECLARE
						mi_mensaje VARCHAR2(2000);
						BEGIN
						mi_mensaje :=
						cunp_promociones_disponibles.carga_promocion (
						un_tipo_promocion  => '$promocion',
						un_periodo   => '$periodo',
						un_programa   => '$programa',
						un_ciclo   => '$ciclo' ,
						un_tipo_inscripcion => '$tipo_inscripcion',
						un_numero_documento => $documento,
						un_nombres => '$nombres',
						un_apellidos => '$apellidos',
						un_email => '$correo',
						un_telefono => '$telefono_fijo',
						un_celular => '$celular',
	        			un_numero_cuotas    => $cuotas,
						un_grupo => '$grupo_facturacion',
						un_respuesta => mi_mensaje
						);
						dbms_output.put_line (
						'Salida: '||mi_mensaje
						);
					 END;";

				
			try {
				
				$consult = oci_parse($db, $query);
				$execution = oci_execute($consult);

	    		// Display the output
				$output = GetDbmsOutput($db);

				foreach ($output as $line)
				
				$status = ['status' => $execution,
						   'output' => $line];
				
			} catch (\Throwable $th) {

				$status = ['status' => $execution .' '. json_encode($th) ];
				
			}

			//guarda el registro
			$sentence = "BEGIN
						CUNP_INSERT_FORMULARIO(
						    SECUENCIA => 0,
						    TIP_DOC => '$tipoDocumento',
						    NUM_DOC => '$documento',
						    P_NOMBRE => '$p_nombre',
						    S_NOMBRE => '$s_nombre',
						    P_APELLIDO => '$p_apellido',
						    S_APELLIDO => '$s_apellido',
						    GENERO => '$genero',
						    F_NACIMIENTO => '".date('d/M/y', strtotime($fecha_nacimiento))."',
						    F_EXPEDICION => '".date('d/M/y', strtotime($fecha_expedicion))."',
						    TEL_FIJO => '$telefono_fijo',
						    TEL_CELULAR => '$celular',
						    CORREO => '$correo',
						    CUBRIMIENTO => '$cubrimiento',
						    M_FINANCIACION => '$promocion',
						    PERIODO => '$periodo',
						    P_ACADEMICO => '$programa',
						    CICLO => '$ciclo',
						    TIP_FORMACION => '$tipo_inscripcion',
						    VALOR => '$valor_matricula',
						    CUOTAS => '$cuotas',
						    TIP_DOC_B =>  NULL,
							NUM_DOC_B =>  NULL, 
							P_NOMBRE_B => NULL,
							S_NOMBRE_B => NULL,
							P_APELLIDO_B => NULL,
							S_APELLIDO_B => NULL,
							GENERO_B => NULL,
							F_NACIMIENTO_B => NULL,
							F_EXPEDICION_B => NULL,
							T_FIJO_B => NULL,
							T_CELULAR_B => NULL,
							CORREO_B => NULL,
							CARGA_PROMOCION => '".json_encode($status)."',
							USUARIO_ID => '$usuario_id',
							GRUPO_FACTURACION => '$grupo_facturacion'
						  );
						END;";

			
			$sentenceExe = oci_parse($db, $sentence);

			$exec = oci_execute($sentenceExe);

			return $status;

		}else{

			//crea la promocion
			$query = "DECLARE
						mi_mensaje VARCHAR2(2000);
						BEGIN
						mi_mensaje :=
						cunp_promociones_disponibles.carga_promocion (
						un_tipo_promocion  => '$promocion',
						un_periodo   => '$periodo',
						un_programa   => '$programa',
						un_ciclo   => '$ciclo' ,
						un_tipo_inscripcion => '$tipo_inscripcion',
						un_numero_documento => $documento,
						un_nombres => '$nombres',
						un_apellidos => '$apellidos',
						un_email => '$correo',
						un_telefono => '$telefono_fijo',
						un_celular => '$celular',
	        			un_numero_cuotas    => '$cuotas',
						un_valor_pago_minimo => 0,
						un_grupo => '$grupo_facturacion',
						un_respuesta => mi_mensaje
						);
						dbms_output.put_line (
						'Salida: '||mi_mensaje
						);
					 END;";
	    				
			try {

				$consult = oci_parse($db, $query);
				$execution = oci_execute($consult);		    	

	    		// Display the output
				$output = GetDbmsOutput($db);

				foreach ($output as $line)
				
				$status = ['status' => $execution,
						   'output' => $line];
				
			} catch (\Throwable $th) {

				$status = ['status' => $execution .' '. json_encode($th) ];

			}		

			$tipo_doc = $info['beneficiario']['tipoDoc'];
			$beneficiario = $info['beneficiario']['documento'];
			$nombres_b = $info['beneficiario']['nombres'];
			$apellidos_b = $info['beneficiario']['apellidos'];
			$correo_b = $info['beneficiario']['correo'];
			$p_nombre_b = $info['beneficiario']['p_nombre'];
			$s_nombre_b = $info['beneficiario']['s_nombre'];
			$p_apellido_b = $info['beneficiario']['p_apellido'];
			$s_apellido_b = $info['beneficiario']['s_apellido'];
			$genero_b = $info['beneficiario']['genero'];
			$fecha_nacimiento_b = $info['beneficiario']['fechaNacimiento'];
			$fecha_expedicion_b = $info['beneficiario']['fechaExpedicion'];
			$telefono_fijo_b = $info['beneficiario']['telefonoFijo'];
			$celular_b = $info['beneficiario']['celular'];

			//guarda el registro

			$sentence = "BEGIN
						CUNP_INSERT_FORMULARIO(
						    SECUENCIA => 0,
						    TIP_DOC => '$tipoDocumento',
						    NUM_DOC => '$documento',
						    P_NOMBRE => '$p_nombre',
						    S_NOMBRE => '$s_nombre',
						    P_APELLIDO => '$p_apellido',
						    S_APELLIDO => '$s_apellido',
						    GENERO => '$genero',
						    F_NACIMIENTO => '".date('d/M/y', strtotime($fecha_nacimiento))."',
						    F_EXPEDICION => '".date('d/M/y', strtotime($fecha_expedicion))."',
						    TEL_FIJO => $telefono_fijo,
						    TEL_CELULAR => $celular,
						    CORREO => '$correo',
						    CUBRIMIENTO => '$cubrimiento',
						    M_FINANCIACION => '$promocion',
						    PERIODO => '$periodo',
						    P_ACADEMICO => '$programa',
						    CICLO => '$ciclo',
						    TIP_FORMACION => '$tipo_inscripcion',
						    VALOR => $valor_matricula,
						    CUOTAS => $cuotas,
						    TIP_DOC_B =>  '$tipo_doc',
							NUM_DOC_B =>  '$beneficiario', 
							P_NOMBRE_B => '$p_nombre_b',
							S_NOMBRE_B => '$s_nombre_b',
							P_APELLIDO_B => '$p_apellido_b',
							S_APELLIDO_B => '$s_apellido_b',
							GENERO_B => '$genero_b',
							F_NACIMIENTO_B => '".date('d/M/y', strtotime($fecha_nacimiento_b))."',
							F_EXPEDICION_B => '".date('d/M/y', strtotime($fecha_expedicion_b))."',
							T_FIJO_B => $telefono_fijo_b,
							T_CELULAR_B => $celular_b,
							CORREO_B => '$correo_b',
							CARGA_PROMOCION => '".json_encode($status)."',
							USUARIO_ID => '$usuario_id',
							GRUPO_FACTURACION => '$grupo_facturacion'
						  );
						END;";

			
			$sentenceExe = oci_parse($db, $sentence);

			$exec = oci_execute($sentenceExe);

			return $status;

		}
		
		
	}

	

	
}
