<?php

class AplicacionPagos{

    public static function getPagos($db){

		$query = "SELECT * from CUNV_PAGOS_PLACETOPAY WHERE ROWNUM <= 10";
		

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

	}

    public static function getPagosState($db, $fecha_desde){

		$query = "SELECT * from CUNV_PAGOS_PLACETOPAY WHERE FECHA = '$fecha_desde' AND STATUS_RES is null";
		

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

	}

    public static function getPagosFechas($db,$fecha_desde){

		$query = "SELECT * from CUNV_PAGOS_PLACETOPAY WHERE FECHA = '$fecha_desde'";
		

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

	}

	public static function getPagosRef($db,$especifica){

		$query = "SELECT * from CUNV_PAGOS_PLACETOPAY WHERE referencia = '$especifica'";
		

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

	}

	public static function getPagosID($db,$especifica){

		$query = "SELECT * from CUNV_PAGOS_PLACETOPAY WHERE identificacion = '$especifica'";
		

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

	}

	public static function getPagosVP($db,$especifica){

		$query = "SELECT * from CUNV_PAGOS_PLACETOPAY WHERE valor_aprobado = '$especifica'";
		

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

	}

	public static function StatePending($db, $referencia){
		$query = "UPDATE ppt_cun_transaccion_pago SET Estado = 'PENDING' WHERE referencia = '$referencia'";

        $update = oci_parse($db, $query);
                
        if(oci_execute($update)){
            return 1;
        }else{
            return 0;
        }
	}

	public static function ProcessResponse($db, $referencia){
		$query = "
		BEGIN
		PPP_CUN_RESPUESTA_PAGO.procesa_pagos_aprobados('$referencia');
		END";

        $consult = oci_parse($db, $query);

        $execution = oci_execute($consult);

        if($execution){
            return 1;
        }else{
            return 0;
        }
	}

	public static function DeleteDetResponse($db, $referencia){
		$query = "DELETE FROM ppt_cun_detalle_respuesta_pago where referencia = '$referencia'";

        $consult = oci_parse($db, $query);

        $execution = oci_execute($consult);

        if($execution){
            return 1;
        }else{
            return 0;
        }
	}

	public static function UpdateValorOrden($db, $referencia){
		$query = "UPDATE PPT_CUN_BASE_ORDENES SET VALOR_PAGADO = 0 WHERE RECIBO_AGRUPADO = '$referencia'";

        $consult = oci_parse($db, $query);

        $execution = oci_execute($consult);

        if($execution){
            return 1;
        }else{
            return 0;
        }
	}

	public static function DeleteResponse($db, $referencia){
		$query = "DELETE FROM ppt_cun_respuesta_pago where referencia = '$referencia;";

        $consult = oci_parse($db, $query);

        $execution = oci_execute($consult);

        if($execution){
            return 1;
        }else{
            return 0;
        }
	}

	public static function UpdateValorCredito($db, $referencia){
		$query = "UPDATE PPT_CUN_BASE_ORDENES SET VALOR_PAGADO = '$valor' WHERE RECIBO_AGRUPADO = '$referencia'";

        $consult = oci_parse($db, $query);

        $execution = oci_execute($consult);

        if($execution){
            return 1;
        }else{
            return 0;
        }
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

}

?>