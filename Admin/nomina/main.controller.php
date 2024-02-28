<?php
date_default_timezone_set('America/Bogota');

class Nomina{

    public static function getNomina($db,$periodo,$cedula){

        if($cedula == '' || $cedula == null || empty($cedula)){

            $query = "SELECT CODIGO_SUCURSAL_EMPLEADOR,NE_PREFIJO,ROW_NUMBER() OVER (ORDER BY TR_NUMERO_IDENTIFICACION) NE_CONSECUTIVO,
            NE_DEVENGADO_TOTAL,NE_DEDUCCION_TOTAL,NE_TIPO_DOCUMENTO,NE_PERIODO_NOMINA,NE_FECHA_LIQUIDACION_INICIO,NE_FECHA_LIQUIDACION_FIN,NE_TIEMPO_LABORADO,
            NE_TIPO_MONEDA,NE_TRM,NE_NOTAS,NE_FECHA_PAGO,NE_CUNE_NOVEDAD,NAE_TIPO_NOTA,NAE_CUNE,TR_TIPO_IDENTIFICACION,TR_NUMERO_IDENTIFICACION,TR_CODIGO,TR_PRIMER_APELLIDO,
            TR_SEGUNDO_APELLIDO,TR_PRIMER_NOMBRE,TR_SEGUNDO_NOMBRE,TR_CORREO_ELECTRONICO,TR_TIPO_TRABAJADOR,TR_SUBTIPO_TRABAJADOR,TR_ALTO_RIESGO_PENSION,TR_LUGAR_TRABAJO_PAIS,
            TR_LUGAR_TRABAJO_DEPARTAMENTO,TR_LUGAR_TRABAJO_MUNICIPIO,TR_LUGAR_TRABAJO_DIRECCION,TR_SALARIO_INTEGRAL,TR_TIPO_CONTRATO,TR_SUELDO,TR_FECHA_INGRESO,
            TR_FECHA_RETIRO,TR_FORMA_PAGO,TR_METODO_PAGO,TR_BANCO,TR_TIPO_CUENTA,TR_NUMERO_CUENTA
            FROM CONSULTA_NOMINA WHERE PERIODO='$periodo'";

        }else{

            $query = "SELECT CODIGO_SUCURSAL_EMPLEADOR,NE_PREFIJO,ROW_NUMBER() OVER (ORDER BY TR_NUMERO_IDENTIFICACION) NE_CONSECUTIVO,
            NE_DEVENGADO_TOTAL,NE_DEDUCCION_TOTAL,NE_TIPO_DOCUMENTO,NE_PERIODO_NOMINA,NE_FECHA_LIQUIDACION_INICIO,NE_FECHA_LIQUIDACION_FIN,NE_TIEMPO_LABORADO,
            NE_TIPO_MONEDA,NE_TRM,NE_NOTAS,NE_FECHA_PAGO,NE_CUNE_NOVEDAD,NAE_TIPO_NOTA,NAE_CUNE,TR_TIPO_IDENTIFICACION,TR_NUMERO_IDENTIFICACION,TR_CODIGO,TR_PRIMER_APELLIDO,
            TR_SEGUNDO_APELLIDO,TR_PRIMER_NOMBRE,TR_SEGUNDO_NOMBRE,TR_CORREO_ELECTRONICO,TR_TIPO_TRABAJADOR,TR_SUBTIPO_TRABAJADOR,TR_ALTO_RIESGO_PENSION,TR_LUGAR_TRABAJO_PAIS,
            TR_LUGAR_TRABAJO_DEPARTAMENTO,TR_LUGAR_TRABAJO_MUNICIPIO,TR_LUGAR_TRABAJO_DIRECCION,TR_SALARIO_INTEGRAL,TR_TIPO_CONTRATO,TR_SUELDO,TR_FECHA_INGRESO,
            TR_FECHA_RETIRO,TR_FORMA_PAGO,TR_METODO_PAGO,TR_BANCO,TR_TIPO_CUENTA,TR_NUMERO_CUENTA
            FROM CONSULTA_NOMINA WHERE PERIODO='$periodo' AND TR_NUMERO_IDENTIFICACION = '$cedula'";


        }


		$select = oci_parse($db, $query);

    	oci_execute($select);
    	
    	$result = array();

    	while($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function getJsonDevengados($db,$periodo,$cedula,$nmro_cuenta){


        $query = "SELECT * FROM cunv_devengados_nomina WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' AND NRO_CONT = '$nmro_cuenta'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }

    public static function getJsonDeducidos($db,$periodo,$cedula,$nmro_cuenta){

        $query = "SELECT * FROM CUNV_DEDUCIDOS_NOMINA WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' AND NRO_CONT = '$nmro_cuenta'";

        $select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }
    
    public static function DevengadosNivel1($db,$periodo,$cedula,$nmro_cuenta){

        $query = "SELECT NIVEL1,VALOR1 FROM cunv_devengados_nomina WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' AND NRO_CONT = '$nmro_cuenta'
        GROUP BY NIVEL1,VALOR1 ORDER BY NIVEL1,VALOR1";

        $select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)){

	        $NIVEL = $row['NIVEL1'];
            $VALOR = $row['VALOR1'];

            if($NIVEL == '' || $NIVEL == NULL || empty($NIVEL)){
                $data = array(
                    'NIVEL1' => 'vacio',
                    'VALOR1' => 'vacio'
                );
            }else{
                $data = array(
                    'NIVEL1' => $NIVEL,
                    'VALOR1' => $VALOR
                );
            }

	        array_push($result, $data);
		}

		return $result;

    }

    public static function DevengadosNivel2($db,$periodo,$cedula,$nmro_cuenta,$nivel1){

        $query = "SELECT NIVEL2,VALOR2 FROM cunv_devengados_nomina WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' 
        AND NRO_CONT = '$nmro_cuenta' AND NIVEL1 = '$nivel1'
        GROUP BY NIVEL2,VALOR2 ORDER BY NIVEL2,VALOR2";

        $select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        
            $NIVEL = $row['NIVEL2'];
            $VALOR = $row['VALOR2'];

            if($NIVEL == '' || $NIVEL == NULL || empty($NIVEL)){
                $data = array(
                    'NIVEL2' => 'vacio',
                    'VALOR2' => 'vacio'
                );
            }else{
                $data = array(
                    'NIVEL2' => $NIVEL,
                    'VALOR2' => $VALOR
                );
            }

	        array_push($result, $data);

		}

		return $result;

    }

    public static function DevengadosNivel3($db,$periodo,$cedula,$nmro_cuenta,$nivel1,$nivel2){

        $query = "SELECT NIVEL3,VALOR3 FROM cunv_devengados_nomina WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' 
        AND NRO_CONT = '$nmro_cuenta' AND NIVEL1 = '$nivel1' AND NIVEL2 = '$nivel2'
        GROUP BY NIVEL3,VALOR3 ORDER BY NIVEL3,VALOR3";

        $select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)){

            $NIVEL = $row['NIVEL3'];
            $VALOR = $row['VALOR3'];

            if($NIVEL == '' || $NIVEL == NULL || empty($NIVEL)){
                $data = array(
                    'NIVEL3' => 'vacio',
                    'VALOR3' => 'vacio'
                );
            }else{
                $data = array(
                    'NIVEL3' => $NIVEL,
                    'VALOR3' => $VALOR
                );
            }

	        array_push($result, $data);
		}

		return $result;

    }

    public static function ValorDevengados($db,$periodo,$cedula,$nmro_cuenta,$nivel1,$nivel2,$nivel3){

        if($nivel1 == '' || $nivel1 == null || empty($nivel1)){
            $query = null;
        }else{

            if($nivel2 == '' || $nivel2 == null || empty($nivel2)){

                //No existe nivel 2, por lo tanto devolver valor nivel 1

                $query = "SELECT VALOR1 AS VALOR FROM cunv_devengados_nomina WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' 
                AND NRO_CONT = '$nmro_cuenta' AND NIVEL1 = '$nivel1'";

            }else{

                if($nivel3 == '' || $nivel3 == null || empty($nivel3)){

                    //No existe nivel 3, por lo tanto devolver valor nivel 2

                    $query = "SELECT VALOR2 AS VALOR FROM cunv_devengados_nomina WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' 
                    AND NRO_CONT = '$nmro_cuenta' AND NIVEL1 = '$nivel1' AND NIVEL2 = '$nivel2'";

                }else{

                    //Devolver valor nivel 3

                    $query = "SELECT VALOR3 AS VALOR FROM cunv_devengados_nomina WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' 
                    AND NRO_CONT = '$nmro_cuenta' AND NIVEL1 = '$nivel1' AND NIVEL2 = '$nivel2' AND NIVEL3 = '$nivel3'";

                }

            }


        }

        if($query == '' || $query == null || empty($query)){
            return 0;
        }else{

            $select = oci_parse($db, $query);
    	
            oci_execute($select);
            
            $result = array();

            while ($row = oci_fetch_array($select, OCI_ASSOC)) {
                array_push($result, $row['VALOR']);
            }

            return $result;

        }

    }


    public static function DeducidosNivel1($db,$periodo,$cedula,$nmro_cuenta){

        $query = "SELECT NIVEL1,VALOR1 FROM CUNV_DEDUCIDOS_NOMINA WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' AND NRO_CONT = '$nmro_cuenta'
        GROUP BY NIVEL1,VALOR1 ORDER BY NIVEL1,VALOR1";

        $select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)){

	        $NIVEL = $row['NIVEL1'];
            $VALOR = $row['VALOR1'];

            if($NIVEL == '' || $NIVEL == NULL || empty($NIVEL)){
                $data = array(
                    'NIVEL1' => 'vacio',
                    'VALOR1' => 'vacio'
                );
            }else{
                $data = array(
                    'NIVEL1' => $NIVEL,
                    'VALOR1' => $VALOR
                );
            }

	        array_push($result, $data);
		}

		return $result;

    }

    public static function DeducidosNivel2($db,$periodo,$cedula,$nmro_cuenta,$nivel1){

        $query = "SELECT NIVEL2,VALOR2 FROM CUNV_DEDUCIDOS_NOMINA WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' 
        AND NRO_CONT = '$nmro_cuenta' AND NIVEL1 = '$nivel1'
        GROUP BY NIVEL2,VALOR2 ORDER BY NIVEL2,VALOR2";

        $select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        
            $NIVEL = $row['NIVEL2'];
            $VALOR = $row['VALOR2'];

            if($NIVEL == '' || $NIVEL == NULL || empty($NIVEL)){
                $data = array(
                    'NIVEL2' => 'vacio',
                    'VALOR2' => 'vacio'
                );
            }else{
                $data = array(
                    'NIVEL2' => $NIVEL,
                    'VALOR2' => $VALOR
                );
            }

	        array_push($result, $data);

		}

		return $result;

    }

    public static function DeducidosNivel3($db,$periodo,$cedula,$nmro_cuenta,$nivel1,$nivel2){

        $query = "SELECT NIVEL3,VALOR3 FROM CUNV_DEDUCIDOS_NOMINA WHERE PERIODO = '$periodo' AND COD_EMPL = '$cedula' 
        AND NRO_CONT = '$nmro_cuenta' AND NIVEL1 = '$nivel1' AND NIVEL2 = '$nivel2'
        GROUP BY NIVEL3,VALOR3 ORDER BY NIVEL3,VALOR3";

        $select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)){

            $NIVEL = $row['NIVEL3'];
            $VALOR = $row['VALOR3'];

            if($NIVEL == '' || $NIVEL == NULL || empty($NIVEL)){
                $data = array(
                    'NIVEL3' => 'vacio',
                    'VALOR3' => 'vacio'
                );
            }else{
                $data = array(
                    'NIVEL3' => $NIVEL,
                    'VALOR3' => $VALOR
                );
            }

	        array_push($result, $data);
		}

		return $result;

    }


    public static function ConceptosDyD($db,$periodo,$cedula){

        if($cedula != '' || $cedula != null){
            $cedula = "AND COD_EMPL = '$cedula'";
        }else{
            $cedula = "";
        }

        $query = "SELECT * FROM cunv_devengados_nomina WHERE PERIODO = '$periodo'".$cedula."
        ORDER BY COD_EMPL,NIVEL1,VALOR1";

        $select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)){

            $COD_EMPL = $row['COD_EMPL'];
            $NRO_CONT = $row['NRO_CONT'];
	        $NIVEL = $row['NIVEL1'];
            $VALOR = $row['VALOR1'];

            if($NIVEL == '' || $NIVEL == NULL || empty($NIVEL)){

            }else{
                array_push($result, $row);
            }

	        
		}

		return $result;

    }
    
    public static function ConceptosDyDU($db,$periodo,$cedula){

        if($cedula != '' || $cedula != null){
            $cedula = "AND COD_EMPL = '$cedula'";
        }else{
            $cedula = "";
        }

        $query = "SELECT * FROM CUNV_DEDUCIDOS_NOMINA WHERE PERIODO = '$periodo'".$cedula."
        ORDER BY COD_EMPL,NIVEL1,VALOR1";

        $select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)){

            $COD_EMPL = $row['COD_EMPL'];
            $NRO_CONT = $row['NRO_CONT'];
	        $NIVEL = $row['NIVEL1'];
            $VALOR = $row['VALOR1'];

            if($NIVEL == '' || $NIVEL == NULL || empty($NIVEL)){

            }else{
                array_push($result, $row);
            }

	        
		}

		return $result;

    }
    


}