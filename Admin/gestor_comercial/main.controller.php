<?php
include '../../libs/config.php';
set_time_limit(500);
class Comerciales{

    public static function getComerciales($db){

        $query = "SELECT * FROM CUN_CONTROL_X_VENDEDOR"; 

        $sql = oci_parse($db, $query);
    	
    	oci_execute($sql);
    	
    	$result = array();

    	while ($row = oci_fetch_array($sql, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }


    public static function ActivarGestor($db,$cedula){

        $query = "SELECT * FROM bas_tercero WHERE num_identificacion='$cedula'"; 

        $cons = oci_parse($db, $query);
    	
    	oci_execute($cons);
    	
    	$result = array();

    	while ($row = oci_fetch_array($cons, OCI_BOTH)) {
	        /* $result[] = $row; */
			$cedula = $row['NUM_IDENTIFICACION'];
			$nombre = $row['NOM_LARGO'];
			$insert = "INSERT INTO CUN_CONTROL_X_VENDEDOR 
						(EMPLEADO,NOMBRE_CLASIFICACION,GRUPO,SUBGRUPO,ESTADO,FECHA_ESTADO) 
						VALUES 
						($cedula,'$nombre','COMERCIALES','VINCULACIONES','A',SYSDATE)";
			$i = oci_parse($db, $insert);
			$result = oci_execute($i);
			if($result){
				return 1;
			} else {
				return 0;
			}
		}

    }

    public static function getDataName($db,$cedula){

        $query = "SELECT * FROM bas_tercero WHERE num_identificacion='$cedula'"; 
    	
    	$sql = oci_parse($db, $query);
    	
    	oci_execute($sql);
    	
    	$result = array();

    	while ($row = oci_fetch_array($sql, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

    }

	public static function commit($db){

        $query = "COMMIT";
  
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    }
}

?>