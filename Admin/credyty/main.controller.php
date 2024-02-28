<?php

class Credyty{

    public static function Consecutivo($db){

        $query = "SELECT  MAX(consecutivo +1)consecutivo FROM cun_enc_legalizados";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
            $consecutivo = $row['CONSECUTIVO'];
		}

        return $consecutivo;
		
    }

    public static function Insert_cun_enc_legalizados($db,$consecutivo,$id_usuario,$descripcion,$archivo){


        $query = "INSERT INTO cun_enc_legalizados (consecutivo,fecha,usuario,descripcion,archivo) 
        values ('$consecutivo',current_timestamp,'$id_usuario','$descripcion','$archivo')";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function loadfile($db,$datosfile){

        $IDENTIFICACION = $datosfile['IDENTIFICACION'];
        $VALOR_FINANCIADO = $datosfile['VALOR_FINANCIADO'];
        $MES = $datosfile['MES'];
        $ITEM = $datosfile['ITEM'];
        $NOMBRE = $datosfile['NOMBRE'];
        $PROGRAMA = $datosfile['PROGRAMA'];
        $SEDE = $datosfile['SEDE'];
        $F_APROVACION = $datosfile['F_APROVACION'];
        $CUOTAS = $datosfile['CUOTAS'];
        $P_PAGO = $datosfile['P_PAGO'];
        $FP_PAGO = $datosfile['FP_PAGO'];
        $V_PAGO = $datosfile['V_PAGO'];
        $N_ORDEN = $datosfile['N_ORDEN'];
        $F_PAGO = $datosfile['F_PAGO'];
        $F_LEGALIZACION = $datosfile['F_LEGALIZACION'];
        $MODALIDAD = $datosfile['MODALIDAD'];
        $CIUDAD = $datosfile['CIUDAD'];
        $SEMESTRE = $datosfile['SEMESTRE'];
        $INTERES = $datosfile['INTERES'];
        $PERIODO = $datosfile['PERIODO'];
        $AVALADOR = $datosfile['AVALADOR'];
        $CONSECUTIVO = $datosfile['CONSECUTIVO'];
        $NOTA_CRE = $datosfile['NOTA_CRE'];

        
        $query = "INSERT INTO cunt_legalizados(IDENTIFICACION,VALOR_FINANCIADO,MES,ITEM, NOMBRE, PROGRAMA, SEDE, F_APROVACION, CUOTAS, P_PAGO, FP_PAGO, 
            V_PAGO, N_ORDEN, F_PAGO, F_LEGALIZACION, MODALIDAD, CIUDAD, SEMESTRE, INTERES, PERIODO, AVALADOR,CONSECUTIVO,NOTA_CRE) 
            VALUES ('$IDENTIFICACION','$VALOR_FINANCIADO','$MES','$ITEM','$NOMBRE','$PROGRAMA','$SEDE',
            TO_DATE('$F_APROVACION', 'YYYY-MM-DD'),
            '$CUOTAS','$P_PAGO',
            TO_DATE('$FP_PAGO', 'YYYY-MM-DD'),
            '$V_PAGO','$N_ORDEN',
            '$F_PAGO',
            TO_DATE('$F_LEGALIZACION', 'YYYY-MM-DD'),
            '$MODALIDAD','$CIUDAD','$SEMESTRE','$INTERES','$PERIODO','$AVALADOR','$CONSECUTIVO','$NOTA_CRE')";
  
/*
        $formato_fecha = 'YYYY-MM-DD"T"HH24:MI:SS.FF3TZR';

        $query = "INSERT INTO cunt_legalizados(IDENTIFICACION,VALOR_FINANCIADO,MES,ITEM, NOMBRE, PROGRAMA, SEDE, F_APROVACION, CUOTAS, P_PAGO, FP_PAGO, 
            V_PAGO, N_ORDEN, F_PAGO, F_LEGALIZACION, MODALIDAD, CIUDAD, SEMESTRE, INTERES, PERIODO, AVALADOR,CONSECUTIVO,NOTA_CRE) 
            VALUES ('$IDENTIFICACION','$VALOR_FINANCIADO','$MES','$ITEM','$NOMBRE','$PROGRAMA','$SEDE',

            to_timestamp_tz('$F_APROVACION', '$formato_fecha'),
            '$CUOTAS','$P_PAGO',

            to_timestamp_tz('$FP_PAGO', '$formato_fecha'),
            '$V_PAGO','$N_ORDEN',
            '$F_PAGO',
            TO_DATE('$F_LEGALIZACION', 'YYYY-MM-DD'),
            '$MODALIDAD','$CIUDAD','$SEMESTRE','$INTERES','$PERIODO','$AVALADOR','$CONSECUTIVO','$NOTA_CRE')";
*/
        $select = oci_parse($db, $query);
                        
        if(oci_execute($select)){
            $result = 1;
        }else{
            $result = 0;
        }

        return $result;

    }

    public static function UpdateOne($db,$consecutivo){

        $query = "UPDATE cunt_legalizados P
        SET P.nota_cre = (SELECT  DISTINCT max(T.nota_credito) nota_credito FROM cunv_nota_legaliza T 
        WHERE P.identificacion=to_number(T.identificacion)
        and P.valor_financiado=T.valor
        and P.mes=T.mes)
        WHERE P.nota_cre=0
        AND CONSECUTIVO  = '$consecutivo'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function UpdateTwo($db,$consecutivo){

        $query = "UPDATE cunt_legalizados P
        SET P.nota_cre = (SELECT  DISTINCT max(T.nota_credito) nota_credito FROM cunv_nota_legaliza T 
        WHERE P.identificacion=to_number(T.identificacion)
        and P.valor_financiado=T.valor)
        WHERE P.nota_cre is null
        AND CONSECUTIVO  = '$consecutivo'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function UpdateTree($db,$consecutivo){

        $query = "UPDATE cunt_legalizados P
        SET P.DESCRIPCION = (SELECT T.DESCRIPCION FROM cunv_nota_legaliza T 
        WHERE P.identificacion=to_number(T.identificacion)
        and P.nota_cre=T.NOTA_CREDITO)
        WHERE CONSECUTIVO = '$consecutivo'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }


    public static function getLoadsFiles($db){

        $query = "SELECT * FROM cun_enc_legalizados
        INNER JOIN cunt_d_usuarios ON cun_enc_legalizados.usuario = cunt_d_usuarios.ID 
        ORDER BY fecha DESC ";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function getDetalleCredyty($db,$consecutivo){

        $query = "SELECT * FROM cunt_legalizados WHERE consecutivo = '$consecutivo' ORDER BY item asc";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }   

}