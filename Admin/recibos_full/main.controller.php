<?php
set_time_limit(500); // 

class RecibosFull{

    public static function getOrdenes($db,$periodo,$cedula,$programa,$ciclo){

        if($cedula == '' || $cedula == null || empty($cedula)){
            $cedula = '';
        }else{
            $cedula = "AND cliente_solicitado IN ('$cedula') "; 
        }

        $query = " SELECT A.*,B.DIR_EMAIL, B.NOM_TERCERO,  B.PRI_APELLIDO
        FROM CUNV_ORDENES_ESTUDIANTES A
        INNER JOIN BAS_TERCERO B ON TO_CHAR(A.CLIENTE_SOLICITADO)=B.NUM_IDENTIFICACION
        WHERE A.COD_PROGRAMA ='$programa' AND A.COD_CICLO = '$ciclo' AND A.PERIODO='$periodo'
        ";
        /* $query = "SELECT * FROM CUNV_ORDENES_ESTUDIANTES 
        WHERE COD_PROGRAMA = '$programa' AND COD_CICLO = '$ciclo' AND PERIODO='$periodo' ".$cedula; */
        
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;   

    }

    public static function getOrdenesCedula($db,$cedula){


        $query = " SELECT A.*,B.DIR_EMAIL, B.NOM_TERCERO, B.PRI_APELLIDO
        FROM CUNV_ORDENES_ESTUDIANTES A
        INNER JOIN BAS_TERCERO B ON TO_CHAR(A.CLIENTE_SOLICITADO)=B.NUM_IDENTIFICACION
        WHERE A.CLIENTE_SOLICITADO ='$cedula'";
        /* $query = "SELECT * FROM CUNV_ORDENES_ESTUDIANTES 
        WHERE COD_PROGRAMA = '$programa' AND COD_CICLO = '$ciclo' AND PERIODO='$periodo' ".$cedula; */
        
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;   

    }

    public static function getData($db,$cedula_estudiante, $periodo_estudiante){

        $query = "SELECT A.*,B.DIR_EMAIL, B.NOM_TERCERO, B.PRI_APELLIDO
        FROM CUNV_ORDENES_ESTUDIANTES A
        INNER JOIN BAS_TERCERO B ON TO_CHAR(A.CLIENTE_SOLICITADO)=B.NUM_IDENTIFICACION
        WHERE A.CLIENTE_SOLICITADO ='$cedula_estudiante' AND A.PERIODO = '$periodo_estudiante'";

        $select = oci_parse($db, $query);
                
        oci_execute($select);

        $result = array();

        while ($row = oci_fetch_array($select, OCI_BOTH)) {
            $result[] = $row;
        }

        return $result;

    }
    public static function getValorFull($db,$periodo,$cod_modalidad,$cod_programa,$cod_ciclo){

        $query = "SELECT * FROM CUNT_PROGRAMA_VALOR WHERE PERIODO = '$periodo' AND COD_MODALIDAD = '$cod_modalidad' 
        AND COD_PROGRAMA = '$cod_programa' AND COD_CICLO = '$cod_ciclo'";


		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        array_push($result, $row);
		}

		return $result;

    }

    public static function UpdateDetalle($db,$orden,$documento,$valor_orden){

        $query = "UPDATE detalle_orden SET precio='$valor_orden', sub_bruto='$valor_orden', 
        sub_total='$valor_orden' WHERE orden='$orden' AND documento='$documento'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function UpdatePrincipal($db,$orden,$documento,$valor_orden){

        $query = "UPDATE orden SET valor_bruto='$valor_orden', valor_total='$valor_orden' 
        WHERE orden='$orden' AND documento='$documento'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function GenerarRecibo($db,$orden,$documento,$valor_orden){

        $query = "DECLARE
        mi_recibo NUMBER;
        BEGIN
        mi_recibo :=
        iceberg.genera_recibo_consignacion (
        un_documento  => '$documento',
        un_orden   => '$orden'
        );
        dbms_output.put_line (
            'Salida: '||mi_recibo
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

    public static function getDatosOrden($db,$orden){

        $query = "SELECT 
        ORD.DOCUMENTO
       ,ORD.ORDEN
       ,ORD.PERIODO
       ,cliente_solicitado
       ,cli.nombre_negocio
       ,uni.est_metodologica COD_MODALIDAD
       ,(SELECT UPPER(GEN.NOM_TABLA) FROM src_generica gen WHERE gen.cod_tabla = uni.est_metodologica  AND GEN.TIP_tabla='ESTMET' ) nom_modalidad
       ,dep.num_apartado COD_PROGRAMA
       ,(SELECT    UPPER(nombre_programa) FROM cun_agrupador_programas WHERE    programa = dep.num_apartado)nom_programa
       ,uni.niv_formacion cod_ciclo
       ,ci.ciclo
       ,ORD.VALOR_TOTAL VALOR_ORDEN
       ,SNS.VALOR VALOR_SEGURO
       ,SNI.VALOR VALOR_INGLES
       ,(ORD.valor_TOTAL+SNS.VALOR+SNI.VALOR) VALOR_MATRICULA
       ,LIQ.LIQUIDACION
       FROM ORDEN ORD
       INNER JOIN detalle_orden DET ON ORD.ORDEN=DET.ORDEN AND ORD.DOCUMENTO=DET.DOCUMENTO
       INNER JOIN v_cliente CLI ON ord.cliente_solicitado=CLI.cliente
       INNER JOIN ordenes_academico ordaca ON  ordaca.documento  = ord.documento AND ordaca.orden  = ord.orden AND ordaca.organizacion  = ord.organizacion
       INNER JOIN src_alum_programa pro ON  pro.id_alum_programa  = ordaca.id_alum_programa      
       INNER JOIN src_uni_academica uni ON  uni.cod_unidad = pro.cod_unidad
       INNER JOIN bas_dependencia dep ON dep.id_dependencia = uni.id_dependencia
       INNER JOIN cunv_ciclos ci on uni.niv_formacion=ci.cod_ciclo
       INNER JOIN SOLICITUD_NOTA SNS ON  ORD.DOCUMENTO=SNS.DOCUMENTO AND ORD.ORDEN=SNS.ORDEN AND ORD.PERIODO=SNS.PERIODO AND SNS.CONCEPTO_NOTA=803 AND SNS.CAUSA_NOTA=826 AND SNS.ESTADO='A'
       INNER JOIN SOLICITUD_NOTA SNI ON  ORD.DOCUMENTO=SNI.DOCUMENTO AND ORD.ORDEN=SNI.ORDEN AND ORD.PERIODO=SNI.PERIODO AND SNI.CONCEPTO_NOTA=701 AND SNI.CAUSA_NOTA=843 AND SNI.ESTADO='A'
       LEFT JOIN LIQUIDACION_ORDEN LIQ ON ORD.DOCUMENTO=LIQ.DOCUMENTO AND ORD.ORDEN=LIQ.ORDEN AND ORD.PERIODO=LIQ.PERIODO AND LIQ.ESTADO='V'
       WHERE ORD.ESTADO='V' and liquidacion is null 
       AND ORD.ORDEN = '$orden'";

        $select = oci_parse($db, $query);
                
        oci_execute($select);

        $result = array();

        while ($row = oci_fetch_array($select, OCI_ASSOC)) {
            array_push($result, $row);
        }

        return $result;


    }

    public static function UpdateIdiomas($db,$cedula,$valor_idiomas,$documento,$orden){

        $query = "UPDATE SOLICITUD_NOTA
        SET VALOR='$valor_idiomas'
        WHERE  CONCEPTO_NOTA=701 AND CAUSA_NOTA=843 AND ESTADO='A' 
        AND CLIENTE='$cedula'
        AND DOCUMENTO='$documento' 
        AND ORDEN='$orden'";

        $select = oci_parse($db, $query);
                
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }
        
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

    public static function commit($db){

        $query = "COMMIT";
  
		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }

    public static function getOrdenesModificadas($db,$fecha_desde,$fecha_hasta,$id_usu){

        $query = "SELECT
        CUNT_D_AUDITORIA.FECHA,
        SUBSTR(CUNT_D_AUDITORIA.DESCRIPCION ,7,8) ORDEN,
        ORDEN.CLIENTE_SOLICITADO,
        BAS_TERCERO.NOM_LARGO,
        ORDEN.PERIODO,
        CENTRO_COSTO.NOMBRE_CENTRO_COSTO,
        CUNT_D_AUDITORIA.DESCRIPCION,
        CUNT_D_USUARIOS.NOMBRE
        FROM CUNT_D_AUDITORIA
        INNER JOIN CUNT_D_USUARIOS ON CUNT_D_USUARIOS.ID = CUNT_D_AUDITORIA.ID_USUARIO
        INNER JOIN ORDEN ON SUBSTR(CUNT_D_AUDITORIA.DESCRIPCION ,7,8)=ORDEN.ORDEN
        INNER JOIN CENTRO_COSTO ON CENTRO_COSTO.CENTRO_COSTO=ORDEN.CENTRO_COSTO
        INNER JOIN BAS_TERCERO ON BAS_TERCERO.NUM_IDENTIFICACION=TO_CHAR(ORDEN.CLIENTE_SOLICITADO)
        WHERE MODULO = 'RF'
        AND FECHA BETWEEN TO_DATE('$fecha_desde 00:00:00', 'YYYY-MM-DD HH24:MI:SS')
        AND TO_DATE('$fecha_hasta 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
        ORDER BY FECHA DESC";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }

    
    public static function ActivarMarcaPago($db,$periodo,$cedula){

        $query = "UPDATE SRC_ENC_LIQUIDACION L set l.est_liquidacion = 2
        WHERE L.ID_ALUM_PROGRAMA IN
        (SELECT  p.id_alum_programa  FROM BAS_TERCERO b,src_alum_programa p WHERE b.id_tercero = p.id_tercero and NUM_IDENTIFICACION in ('$cedula'))
        and l.cod_periodo in ('$periodo')";

        $select = oci_parse($db, $query);

        if(oci_execute($select)){
           $commit = "COMMIT";
           $transact = oci_parse($db,$commit);
           $result = oci_execute($transact)?1:0;
           $newTransact = oci_commit($db)?1:0;
           
           return $newTransact;
        }else{
            return 0;
        }

    } 


    public static function QuitarMarcaPago($db,$periodo,$cedula){

        $query = "UPDATE SRC_ENC_LIQUIDACION L set l.est_liquidacion = 1
        WHERE L.ID_ALUM_PROGRAMA IN 
        (SELECT  p.id_alum_programa  FROM BAS_TERCERO b,src_alum_programa p WHERE b.id_tercero = p.id_tercero and NUM_IDENTIFICACION in ('$cedula'))
        and l.cod_periodo in ('$periodo')";

        $select = oci_parse($db, $query);
                        
        if(oci_execute($select)){
            return 1;
        }else{
            return 0;
        }

    }
    
    public static function ActivarCunVive($db,$periodo,$cedula){

        $query = "INSERT INTO CUNT_ZOHO_REGISTRO_VIRTUAL (SECUENCIA,FECHA_REGISTRO,FECHA_ESTADO,ESTADO,NOMBRES,APELLIDOS,EMAIL,TELEFONO
        ,TIPO_DOCUMENTO,NUMERO_DOCUMENTO,PROGRAMA,PROMOCION,MODALIDAD,PERIODO)
        SELECT 
        (SELECT MAX(SECUENCIA)+1 FROM CUNT_ZOHO_REGISTRO_VIRTUAL) SECUENCIA 
        ,SYSDATE FECHA_REGISTRO 
        ,SYSDATE FECHA_ESTADO   
        ,'INICIAL' ESTADO    
        ,CLI.NOMBRE_RAZON_SOCIAL NOMBRES    
        ,CLI.PRIMER_APELLIDO||' '||CLI.SEGUNDO_APELLIDO APELLIDOS   
        ,CLI.DIRECCION_ELECTRONICA EMAIL    
        ,CLI.TELEFONO TELEFONO  
        ,CLI.TIPO_IDENTIFICACION TIPO_DOCUMENTO 
        ,ORD.CLIENTE_SOLICITADO NUMERO_DOCUMENTO    
        ,(SELECT UPPER(nombre_programa) FROM cun_agrupador_programas WHERE    programa = dep.num_apartado ) PROGRAMA 
        ,'8007' PROMOCION   
        ,(SELECT UPPER(GEN.NOM_TABLA) FROM src_generica gen  WHERE gen.cod_tabla = uni.est_metodologica AND GEN.TIP_tabla='ESTMET' ) MODALIDAD  
        ,ORD.PERIODO PERIODO
        FROM ORDEN ORD 
        LEFT JOIN liquidacion_orden liq on liq.orden = ord.orden and liq.organizacion_originado = ord.organizacion and liq.documento_originado = ord.documento
        LEFT JOIN v_cliente cli ON ord.cliente_solicitado = cli.cliente
        LEFT JOIN ordenes_academico ordaca ON ordaca.documento  = ord.documento AND  ordaca.orden  = ord.orden AND    ordaca.organizacion  = ord.organizacion
        LEFT JOIN src_alum_programa pro ON pro.id_alum_programa  = ordaca.id_alum_programa
        LEFT JOIN src_uni_academica uni ON uni.cod_unidad = pro.cod_unidad 
        LEFT JOIN bas_dependencia dep ON  dep.id_dependencia = uni.id_dependencia
        WHERE ORD.DOCUMENTO='FAMA' AND LIQ.LIQUIDACION IS NULL AND ORD.ESTADO='V' AND ORD.PERIODO='$periodo' AND CLIENTE_SOLICITADO='$cedula'";

        $select = oci_parse($db, $query);
        if(oci_execute($select)){
                return 1;
        }  else {
                return 0;
        }

    }

}
