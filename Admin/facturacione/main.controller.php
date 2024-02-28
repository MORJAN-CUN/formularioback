<?php

class FacturacionE{

    public static function getDatosF($db,$factura){

        $query = "SELECT LIQUIDACION,TO_CHAR(FECHA,'YYYYMM')PERIODO_M,FECHA FECHA_INICIAL,FECHA_VENCIMIENTO,FECHA_ELABORA,PERIODO,ESTADO,MOTIVO_ANULACION,FONDO,
        FUENTE_FUNCION,CLIENTE,DOCUMENTO,ORGANIZACION,EMPLEADO,CONDICION_COMERCIAL
        CENTRO_COSTO,ORGANIZACION_CENTRO,ORDEN,GENERA_MORA,TASA_INTERES_MORA,FECHA_LIQUIDACION_INTERES,DESCRIPCION,MENSAJE,VALOR_BRUTO,DESCUENTO,VALOR_DESCUENTO,VALOR_IVA,VALOR_TOTAL 
        FROM LIQUIDACION_ORDEN WHERE (LIQUIDACION=$factura) AND DOCUMENTO='FACO'";

        $select = oci_parse($db, $query);
                
        oci_execute($select);

        $result = array();

        while ($row = oci_fetch_array($select, OCI_ASSOC)) {
            array_push($result, $row);
        }

        return $result;

    }

    public static function MovimientoMes($db,$periodo_m){

        $query = "
        BEGIN
        cunp_facele_movimiento_base.recupera_movimiento (
            un_periodo  => '$periodo_m'
        );
        END;";

        $consult = oci_parse($db, $query);

        $execution = oci_execute($consult);

        if($execution){
           return 1;
        }else{
           return 0;
        }

    }

    public static function getDataInfo($db,$num_factura){

        $query = "SELECT
        per.secuencia_persona
        ,per.identificacion
        ,per.nombre_razon_social
        ,per.direccion_electronica
        ,ord.valor_total
        FROM
        persona per
        ,liquidacion_orden ord
        WHERE to_char(ord.cliente) = per.identificacion
        AND ord.documento='FACO'
        AND ord.liquidacion='$num_factura'"; 
    	
    	$sql = oci_parse($db, $query);
    	
    	oci_execute($sql);
    	
    	$result = array();

    	while ($row = oci_fetch_array($sql, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function Cabecera($db,$fecha_inicial,$fecha_final,$clase,$secuencia_persona,$identificacion,$documento,$nmro_credito){

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

        $query = "DECLARE
            mi_mensaje VARCHAR2(2000);
            BEGIN
            mi_mensaje :=
            cunp_facele_cabecera_factura.agrupa_base_x_periodo (
                un_fecha_inicial  => '$fecha_inicial',
                un_fecha_final   => '$fecha_final',
                un_clase   => '$clase',
                un_secuencia_persona   => '$secuencia_persona' ,
                un_identificacion => '$identificacion',
                un_documento => '$documento',
                un_numero_credito => '$nmro_credito'
            );
            dbms_output.put_line (
            'Salida: '||mi_mensaje
            );
        END;";

        $consult = oci_parse($db, $query);

        $execution = oci_execute($consult);

        if($execution){
           
            // Display the output
            $output = GetDbmsOutput($db);

            foreach ($output as $line){
            
            $status = ['status' => $execution,
                        'output' => $line];

            }
            return $status;

        }else{
           
            $status = ['status' => 0,
                        'output' => 'Error ejecutando el proceso'];

            return $status;

        }

    }
    

    public static function Remuneracion($db){

        $query = "
        BEGIN
            cunp_facele_cabecera_factura.renumeracion_para_envio;
            COMMIT;
        END;";

        $consult = oci_parse($db, $query);

        $execution = oci_execute($consult);

        if($execution){
           return 1;
        }else{
           return 0;
        }

    }
    

    public static function EnvioEbill($db){

        $query = "
        BEGIN
            cunp_facele_cabecera_factura.registra_envio_ebill;
            COMMIT;
        END;";

        $consult = oci_parse($db, $query);

        $execution = oci_execute($consult);

        if($execution){
           return 1;
        }else{
           return 0;
        }

    }

    public static function getDocElectronico($db,$identificacion){

        $query = "SELECT MAX(SEC_DOCUMENTO_ELECTRONICO) DOCUMENTO_ELECTRONICO from fev_documento_electronico where identificacion='$identificacion' ";

        $select = oci_parse($db, $query);
                
        oci_execute($select);

        $result = array();

        while ($row = oci_fetch_array($select, OCI_ASSOC)) {
            array_push($result, $row);
        }

        return $result;

    }
    
    public static function JobEbill($db,$documento_electronico){

        $query = "
        BEGIN
                FOR doc_electronico IN (
                    SELECT doc.sec_documento_electronico secuencia
                    FROM fet_documento_electronico doc
                    WHERE doc.estado IN ('S') and  sec_documento_electronico in ('$documento_electronico')
                )LOOP 
                    ICE_FE.EnviarDocumentoElectronico(
                        un_sec_documento_electronico => doc_electronico.secuencia
                    );	
                    COMMIT;
                END LOOP;
        END;";

        $consult = oci_parse($db, $query);

        $execution = oci_execute($consult);

        if($execution){
           return 1;
        }else{
           return 0;
        }

    }


    public static function FacturasCargadas($db){

        $query = "SELECT 
        TRIM(SUBSTRB(DESCRIPCION, 9,8)) AS FACTURA,
        FECHA
        FROM CUNT_D_AUDITORIA 
        WHERE CUNT_D_AUDITORIA.MODULO = 'FE' ORDER BY CUNT_D_AUDITORIA.FECHA DESC";

        $select = oci_parse($db, $query);
                
        oci_execute($select);

        $result = array();

        while ($row = oci_fetch_array($select, OCI_ASSOC)) {
            array_push($result, $row);
        }

        return $result;

    }
    
}