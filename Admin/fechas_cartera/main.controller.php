<?php

class FechasCartera{

    public static function UpdateFecha($db,$cedula_estudiante,$periodo,$nota_debito,$fecha_vencimiento){

        $query = "UPDATE nota_debito 
        SET fecha_vencimiento= TO_DATE('$fecha_vencimiento', 'DD-MM-YYYY')
        WHERE cliente='$cedula_estudiante ' AND periodo='$periodo' AND nota_debito='$nota_debito'";

        $select = oci_parse($db, $query);
                                
        if(oci_execute($select)){
            $result = 1;
        }else{
            $result = oci_error($select);
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

    public static function getDatosEstudiante($db, $cedula){

        $query = "SELECT A.fecha,A.nota_debito,A.cliente,A.periodo,A.descripcion,A.valor_total,A.fecha_vencimiento
        from nota_debito A 
        inner join cunv_control_periodos p on a.periodo=p.periodo
        where A.concepto_nota='701' and A.causa_nota='714' and defecto='S' AND CLIENTE = '$cedula'";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_ASSOC)) {
	        $result[] = $row;
		}

		return $result;

    }

}