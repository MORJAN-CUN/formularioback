<?php

class AdminController{

	public static function getLogin($db,$user,$pass){

		$query = "SELECT * from CUNT_D_USUARIOS WHERE email = '$user' AND PASSWORD = '$pass' ";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$id = null;

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $id = $row['ID'];
			$perfil = $row['PERFIL'];
		}

		if($id == '' || $id == null || empty($id)){
			$id = 0;
		}

		if($perfil == '' || $perfil == null || empty($perfil)){
			$perfil = 0;
		}

		$result = array(
			'id' => $id,
			'perfil' => $perfil
		);

		return $result;

	}


	public static function getDataEmpleado($db,$id_empleado){

		$query = "SELECT * from CUNT_D_USUARIOS
		LEFT join CUNT_D_ROLESUSER on CUNT_D_USUARIOS.perfil = CUNT_D_ROLESUSER.id_perfil
 		WHERE id = '$id_empleado' ";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
			$id_usu = $row['ID'];
	        $nombre = $row['NOMBRE'];
	        $email = $row['EMAIL'];
	        $accesos = $row['ACCESOS'];
	        $password = $row['PASSWORD'];
			$cedula = $row['CEDULA'];
		}

		$data = array(
			'id_usu' => $id_usu,
			'nombre' => $nombre,
			'email' => $email,
			'accesos' => $accesos,
			'password' => $password,
			'cedula' => $cedula
		);
		
		return $data;

	}

	public static function getEmpleados($db){

		//$query = "SELECT * from CUNT_D_USUARIOS ORDER BY NOMBRE ASC";
        $query = "SELECT 
        CUNT_D_USUARIOS.ID,
        CUNT_D_USUARIOS.NOMBRE,
        CUNT_D_USUARIOS.EMAIL,
        CUNT_D_USUARIOS.PASSWORD,
        CUNT_D_USUARIOS.ESTADO,
        CUNT_D_USUARIOS.FECHA_CRE,
        CUNT_D_USUARIOS.PERFIL,
        CUNT_D_USUARIOS.CEDULA,
        CUNT_D_ROLESUSER.NOM_PERFIL
        from CUNT_D_USUARIOS 
        LEFT JOIN CUNT_D_ROLESUSER ON CUNT_D_ROLESUSER.ID_PERFIL = CUNT_D_USUARIOS.PERFIL
        ORDER BY NOMBRE ASC";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

	}

	public static function InsertPerfil($db,$nom_rol,$est_rol,$accesos){

		$query = "INSERT INTO CUNT_D_ROLESUSER (nom_perfil,estado_p,accesos,fecha_cre) VALUES ('$nom_rol','$est_rol','$accesos',current_timestamp)";
		
		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
    		return 1;
    	}else{
    		return 0;
    	}
    	

	}

	public static function getPerfiles($db){

		$query = "SELECT * from CUNT_D_ROLESUSER order by fecha_cre DESC";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

	}

	public static function UpdatePerfilEmpl($db,$id,$perfil){

		$query = "UPDATE cunt_d_usuarios SET perfil = '$perfil' WHERE id = '$id'";
		
		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
    		return 1;
    	}else{
    		return 0;
    	}

	}

	public static function getDataPerfil($db,$id){

		$query = "SELECT * from CUNT_D_ROLESUSER WHERE id_perfil = '$id'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
    	$result = array();

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $result[] = $row;
		}

		return $result;

	}

	public static function UpdatePerfil($db,$id,$nombre,$estado,$accesos){

		$query = "UPDATE CUNT_D_ROLESUSER SET nom_perfil = '$nombre', estado_p = '$estado', accesos = '$accesos' WHERE id_perfil = '$id'";
		
		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
    		return 1;
    	}else{
    		return 0;
    	}

	}

	public static function UpdatePassword($db,$id_user,$pass_new){

		$query = "UPDATE cunt_d_usuarios SET PASSWORD = '$pass_new' WHERE ID = '$id_user'";
		
		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
    		return 1;
    	}else{
    		return 0;
    	}

	}

	public static function getNewPass($db,$email,$cedula){

		$query = "SELECT * from CUNT_D_USUARIOS WHERE EMAIL = '$email' AND CEDULA = '$cedula'";
		
		$select = oci_parse($db, $query);
    	
    	oci_execute($select);
    	
		$exis = null;

    	while ($row = oci_fetch_array($select, OCI_BOTH)) {
	        $exis = 1;
		}

		if($exis == '' || $exis == null || empty($exis)){
			$exis = 0;
		}

		return $exis;

	}

	public static function periodosActivos($db){
		$response = [];

		$query = "SELECT PERIODO, NOM_PERIODO FROM cunv_periodos_activos WHERE defecto = 'S'";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	while($x = oci_fetch_array($select,OCI_BOTH)){
    		$data = ['periodo' => $x['PERIODO'],
    				 'nom_periodo' => $x['NOM_PERIODO']];

    		array_push($response, $data);		 
    	}

    	return $response;
	}
	public static function perfilesActivos($db){
		$response = [];

		$query = "SELECT ID_PERFIL, NOM_PERFIL FROM cunt_d_rolesuser";

		$select = oci_parse($db, $query);
    	
    	oci_execute($select);

    	while($x = oci_fetch_array($select,OCI_BOTH)){
    		$data = ['id' => $x['ID_PERFIL'],
    				 'nom_perfil' => $x['NOM_PERFIL']];

    		array_push($response, $data);		 
    	}

    	return $response;
	}

	public static function quitarPerfil($db,$id){
		$query = "UPDATE cunt_d_usuarios SET perfil = '2' WHERE id = '$id'";
		
		$select = oci_parse($db, $query);
    	
    	if(oci_execute($select)){
    		return 1;
    	}else{
    		return 0;
    	}
	}

	public static function getPerfil($db,$option){
		$query = "SELECT 
        CUNT_D_USUARIOS.ID,
        CUNT_D_USUARIOS.NOMBRE,
        CUNT_D_USUARIOS.EMAIL,
        CUNT_D_USUARIOS.PASSWORD,
        CUNT_D_USUARIOS.ESTADO,
        CUNT_D_USUARIOS.FECHA_CRE,
        CUNT_D_USUARIOS.PERFIL,
        CUNT_D_USUARIOS.CEDULA,
        CUNT_D_ROLESUSER.NOM_PERFIL
        from CUNT_D_USUARIOS
        LEFT JOIN CUNT_D_ROLESUSER ON CUNT_D_ROLESUSER.ID_PERFIL = CUNT_D_USUARIOS.PERFIL
        where PERFIL = '$option'";

		$select = oci_parse($db, $query);
				
		oci_execute($select);

		$result = array();

		while ($row = oci_fetch_array($select, OCI_BOTH)) {
			$result[] = $row;
		}

		return $result;
	}


}