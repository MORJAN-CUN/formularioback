<?php

class ParametrosController{

	public static function getLogin($db,$user,$pass){

               

		$query = "SELECT * from CUNT_D_USUARIOS WHERE email = '$user' AND PASSWORD = '$pass' ";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$id = null;

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $id = $row['ID'];
		}

		if($id == '' || $id == null || empty($id)){
			$id = 0;
		}

		return $id;

	}


	public static function getPromociones($db,$dato){

		$query = "SELECT * FROM CUN_AGRUPADOR_PROMOCIONES";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $nombre = $row['NOMBRE'];
	        $email = $row['EMAIL'];
		}

		$data = array(
			'nombre' => $nombre,
			'email' => $email
		);
		
		return $data;

	}

	public static function getPeriodo($db,$dato){

		$query = "SELECT UNIQUE (PE.COD_PERIODO),PE.NOM_PERIODO
				  FROM SRC_PERIODO p INNER JOIN SRC_ENC_PERIODO PE ON PE.COD_PERIODO = P.COD_PERIODO
				  INNER JOIN SRC_ACT_ACADEMICA A ON A.COD_UNIDAD = P.COD_UNIDAD WHERE A.VAL_ACTIVIDAD = '101'
				  AND P.COD_PERIODO = A.COD_PERIODO AND A.FEC_FIN >= SYSDATE and p.nom_periodo NOT like'%idioma%'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

		$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
			$data = ['periodo' => $row['COD_PERIODO'],
					 'nombre' => $row['NOM_PERIODO'] 
					];

			array_push($response,$data);
		}

		return $response;

	}

	public static function getPeriodoIdiomas($db,$periodo){

		$query = "SELECT DISTINCT PERIODO_IDIOMAS
					FROM cunt_promo_parametro_x_periodo
					WHERE ESTADO = 'A'
					AND PERIODO = '$periodo'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

		$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
			$data = ['periodo_idiomas' => $row['PERIODO_IDIOMAS'] 
					];

			array_push($response,$data);
		}

		return $response;

	}
	
	public static function getPeriodoIdiomas2($db){
        /*
		$query = "SELECT UNIQUE (PE.COD_PERIODO),
		PE.NOM_PERIODO,
		R.PERIODOA
		FROM SRC_PERIODO p
		INNER JOIN SRC_ENC_PERIODO PE ON PE.COD_PERIODO = P.COD_PERIODO
		INNER JOIN SRC_ACT_ACADEMICA A ON A.COD_UNIDAD = P.COD_UNIDAD
		INNER JOIN CUNT_RELACION_PERIODO R ON PE.COD_PERIODO=R.PERIODOI
		WHERE A.VAL_ACTIVIDAD = '101'
		AND P.COD_PERIODO = A.COD_PERIODO
		AND A.FEC_FIN >= SYSDATE and p.nom_periodo like'%idioma%'";
        */

        $query = "SELECT DISTINCT
        C.PERIODO_IDIOMAS FROM SRC_PERIODO A
        INNER JOIN SRC_ENC_PERIODO B ON A.COD_PERIODO = B.COD_PERIODO
        INNER JOIN cunt_promo_parametro_x_periodo C ON A.COD_PERIODO=C.PERIODO
        WHERE  B.FEC_FIN>=SYSDATE-60";

		$select = oci_parse($db,$query);
		oci_execute($select);
		$response = [];
		while($row = oci_fetch_array($select, OCI_BOTH)){
			$data = ['periodo_idiomas' => $row['PERIODO_IDIOMAS']
					];
			array_push($response,$data);
		}

		return $response;

	}



	public static function getPrograma($db,$periodo){

		$query = "SELECT * FROM CUN_AGRUPADOR_PROGRAMAS";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

		$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
			$data = ['nombre' => $row['NOMBRE_PROGRAMA'] 
					];

			array_push($response,$data);
		}

		return $response;

	}

	public static function getCiclo($db,$periodo,$programa){

		$query = "SELECT * FROM CUNV_CICLOS";
		
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

	public static function getInscripcion($db,$periodo,$programa,$ciclo){

		$query = "SELECT * FROM  CUN_AGRUPADOR_INSCRIPCION";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

		$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
			$data = ['descripcion' => $row['DESCRIPCION'] 
					];

			array_push($response,$data);
		}

		return $response;

	}

	public static function getfinanciaion($db,$dato){

		$query = "SELECT * FROM CUN_AGRUPADOR_PROMOCIONES";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

		$response = [];

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
			$data = ['descripcion' => $row['DESCRIPCION'] 
					];

			array_push($response,$data);
		}

		return $response;

	}

	public static function Selecsecuencia($db){

		$query = "SELECT  MAX(SECUENCIA +1)SECUENCIA FROM CUNT_PROMOCIONES_DISPONIBLES";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$id = null;

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $id = $row['SECUENCIA'];
		}

		if($id == '' || $id == null || empty($id)){
			$id = 0;
		}

		return $id;
    	

	}

	public static function InsertPromocion($db,$estado,$fechaRegistro,$tipoPromocion,$periodo,$periodoIdiomas,$programa,$ciclo,$tipoInscripcion,
	$valorMatricula,$valorIdioma,$valorServicio,$cuotas,$porcMatricula,$porcIdiomas,$id,$cunvive,$C_2X1){

		$query = "INSERT INTO CUNT_PROMOCIONES_DISPONIBLES (ESTADO,FECHA_REGISTRO,TIPO_PROMOCION,PERIODO,PERIODO_IDIOMAS,
		PROGRAMA,CICLO,TIPO_INSCRIPCION,VALOR_MATRICULA,VALOR_IDIOMAS,VALOR_SERVICIO,NUMERO_CUOTAS,PORC_MATRICULA,PORC_IDIOMAS,VAL_TRASLADO_MATRICULA,
		VAL_TRASLADO_IDIOMAS,VAL_PROM_BENEFICIARIO,ES_CUN_VIVE,ES_2X1) 
		VALUES ('$estado',TO_DATE('$fechaRegistro', 'YYYY-MM-DD'),'$tipoPromocion','$periodo','$periodoIdiomas','$programa',
		'$ciclo','$tipoInscripcion','$valorMatricula','$valorIdioma','$valorServicio','$cuotas','$porcMatricula','$porcIdiomas','0','0','0',
		'$cunvive','$C_2X1')";

		//return $query;
		
		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
    		return 1;
    	}else{
    		return 0;
    	}
    	
	}


	public static function UpdatePromocion($db,$id_promocion,$estado,$fecha_registro,$tipo_promocion,$periodo,$periodo_idiomas,$programa,$ciclo,
		$tipo_inscripcion,$numero_cuotas,$valor_matricula,$valor_idiomas,$valor_servicio,$porc_matricula,$porc_idiomas,$es_cun_vive,$es_2x1,$tipoupd){

		if($tipoupd == 1){

		$query = "UPDATE CUNT_PROMOCIONES_DISPONIBLES SET 
		ESTADO = '$estado',
		FECHA_REGISTRO = TO_DATE('$fecha_registro', 'YYYY-MM-DD'),
		VALOR_MATRICULA = '$valor_matricula',
		VALOR_IDIOMAS = '$valor_idiomas',
		VALOR_SERVICIO ='$valor_servicio',
		NUMERO_CUOTAS = '$numero_cuotas',
		PORC_MATRICULA = '$porc_matricula',
		PORC_IDIOMAS = '$porc_idiomas',
		ES_CUN_VIVE = '$es_cun_vive',
		ES_2X1 = '$es_2x1'  WHERE SECUENCIA = '$id_promocion'";

		}else{

		$query = "UPDATE CUNT_PROMOCIONES_DISPONIBLES SET 
		ESTADO = '$estado',
		FECHA_REGISTRO = TO_DATE('$fecha_registro', 'YYYY-MM-DD'),
		TIPO_PROMOCION = '$tipo_promocion',
		PERIODO = '$periodo',
		PERIODO_IDIOMAS = '$periodo_idiomas',
		PROGRAMA = '$programa',
		CICLO = '$ciclo',
		TIPO_INSCRIPCION = '$tipo_inscripcion',
		VALOR_MATRICULA = '$valor_matricula',
		VALOR_IDIOMAS = '$valor_idiomas',
		VALOR_SERVICIO ='$valor_servicio',
		NUMERO_CUOTAS = '$numero_cuotas',
		PORC_MATRICULA = '$porc_matricula',
		PORC_IDIOMAS = '$porc_idiomas',
		ES_CUN_VIVE = '$es_cun_vive',
		ES_2X1 = '$es_2x1'  WHERE SECUENCIA = '$id_promocion'";

		}	
		
		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
    		return 1;
    	}else{
    		return 0;
    	}

	}


	public static function getPromocionesCreadas($db){

		$query = "SELECT *FROM CUNT_PROMOCIONES_DISPONIBLES ORDER BY SECUENCIA DESC";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

	}
	
	public static function getDatosPromociones($db,$id){

		$query = "SELECT * from CUNT_PROMOCIONES_DISPONIBLES WHERE SECUENCIA = '$id'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;


	}
	
	public static function getDatosPromocion($db,$id){

		$query = "SELECT * FROM CUNT_PROMOCIONES_DISPONIBLES WHERE SECUENCIA = '$id'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;


	}


	public static function InsertParamPeriodo($db,$periodo,$tipoPromocion,$fechaRegistro,$fechaFinal,$periodoIdiomas,$cuotas,$porcpagar){

		$query = "INSERT INTO cunt_promo_parametro_x_periodo (periodo,tipo_promocion,fecha_registro,fecha_final,periodo_idiomas,numero_cuotas,porc_a_pagar) 
			VALUES ('$periodo','$tipoPromocion',TO_DATE('$fechaRegistro', 'YYYY-MM-DD') ,TO_DATE('$fechaFinal', 'YYYY-MM-DD'),'$periodoIdiomas','$cuotas','$porcpagar')";
		
		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
    		return 1;
    	}else{
    		return 0;
    	}
    	

	}

	public static function getPeriodosCreados($db){

		$query = "SELECT * FROM cunt_promo_parametro_x_periodo";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

	}

	
	public static function getDatosPeriodos($db,$id){

		$query = "SELECT * from cunt_promo_parametro_x_periodo WHERE id = '$id'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;


	}

	public static function UpdatePeriodo($db,$id_periodo,$periodo,$tipo_financiacion,$fecha_registro,$fecha_final,$periodo_idiomas,$numero_cuotas,$porc_a_pagar,$tipoupd){

		if($tipoupd == 1){

			$query = "UPDATE cunt_promo_parametro_x_periodo SET 
			FECHA_REGISTRO = TO_DATE('$fecha_registro', 'YYYY-MM-DD'),
			FECHA_FINAL = TO_DATE('$fecha_final', 'YYYY-MM-DD'), 
			PERIODO_IDIOMAS = '$periodo_idiomas', NUMERO_CUOTAS = '$numero_cuotas', PORC_A_PAGAR = '$porc_a_pagar'
			WHERE ID = '$id_periodo'";

		}else{

			$query = "UPDATE cunt_promo_parametro_x_periodo SET PERIODO = '$periodo', TIPO_PROMOCION = '$tipo_financiacion', 
			FECHA_REGISTRO = TO_DATE('$fecha_registro', 'YYYY-MM-DD'),
			FECHA_FINAL = TO_DATE('$fecha_final', 'YYYY-MM-DD'), 
			PERIODO_IDIOMAS = '$periodo_idiomas', NUMERO_CUOTAS = '$numero_cuotas', PORC_A_PAGAR = '$porc_a_pagar'
			WHERE ID = '$id_periodo'";

		}

		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
    		return 1;
    	}else{
    		return 0;
    	}

	}

	public static function existPeriodo($db,$periodo,$tipo_financiacion){

		$query = "SELECT * from cunt_promo_parametro_x_periodo WHERE PERIODO = '$periodo' AND TIPO_PROMOCION = '$tipo_financiacion'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$exist = 0;

    	if($row = oci_fetch_array($select, OCI_BOTH)) {
	        $exist = 1;
		}else{
			$exist = 0;
		}

		return $exist;

	}

	public static function ExistPromocion($db,$tipoPromocion,$periodo,$periodoIdiomas,$programa,$ciclo,$tipoInscripcion){

		$query = "SELECT * FROM CUNT_PROMOCIONES_DISPONIBLES 
		WHERE TIPO_PROMOCION = '$tipoPromocion' AND PERIODO = '$periodo' AND PERIODO_IDIOMAS  = '$periodoIdiomas' AND PROGRAMA = '$programa' 
		AND CICLO = '$ciclo' AND TIPO_INSCRIPCION  = '$tipoInscripcion' ";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$exist = 0;

    	if($row = oci_fetch_array($select, OCI_BOTH)) {
	        $exist = 1;
		}else{
			$exist = 0;
		}

		return $exist;

	}

	public static function ExistPromocionEdit($db,$tipo_promocion,$periodo,$periodo_idiomas,$programa,
											  $ciclo,$tipo_inscripcion){

		$query = "SELECT * FROM CUNT_PROMOCIONES_DISPONIBLES 
		WHERE TIPO_PROMOCION = '$tipo_promocion' AND PERIODO = '$periodo' AND PERIODO_IDIOMAS  = '$periodo_idiomas' AND PROGRAMA = '$programa' AND CICLO = '$ciclo' AND TIPO_INSCRIPCION  = '$tipo_inscripcion'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$exist = 0;

    	if($row = oci_fetch_array($select, OCI_BOTH)) {
	        $exist = 1;
		}else{
			$exist = 0;
		}

		return $exist;

	}

	public static function getPromocionesCreadasPeriodo($db,$periodo){

		$query = "SELECT * FROM CUNT_PROMOCIONES_DISPONIBLES WHERE PERIODO = '$periodo' ORDER BY TIPO_PROMOCION ,PROGRAMA, TIPO_INSCRIPCION ASC";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;
		
	}

	public static function getDataFinanciacion($db,$secuencia){

		$query = "SELECT * FROM CUNT_PROMOCIONES_DISPONIBLES WHERE SECUENCIA = '$secuencia'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

	}

	public static function InsertPromocionMasivo($db,$estado,$fechaRegistro,$tipoPromocion,$periodo,$periodoIdiomas,$programa,$ciclo,$tipoInscripcion,
	$valorMatricula,$valorIdioma,$valorServicio,$cuotas,$porcMatricula,$porcIdiomas,$id,$cunvive,$C_2X1){

		$query = "INSERT INTO CUNT_PROMOCIONES_DISPONIBLES (ESTADO,FECHA_REGISTRO,TIPO_PROMOCION,PERIODO,PERIODO_IDIOMAS,
		PROGRAMA,CICLO,TIPO_INSCRIPCION,VALOR_MATRICULA,VALOR_IDIOMAS,VALOR_SERVICIO,NUMERO_CUOTAS,PORC_MATRICULA,PORC_IDIOMAS,VAL_TRASLADO_MATRICULA,
		VAL_TRASLADO_IDIOMAS,VAL_PROM_BENEFICIARIO,ES_CUN_VIVE,ES_2X1) 
		VALUES ('$estado',TO_DATE('$fechaRegistro', 'YYYY-MM-DD'),'$tipoPromocion','$periodo','$periodoIdiomas','$programa',
		'$ciclo','$tipoInscripcion','$valorMatricula','$valorIdioma','$valorServicio','$cuotas','$porcMatricula','$porcIdiomas','0','0','0',
		'$cunvive','$C_2X1')";

		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
			return 1;
		}else{
			return 0;
		}
    
	}


}
