<?php

function InsertLog($db_conn,$cedula,$id_usuario,$modulo,$tipo,$descripcion){

    $query = "INSERT INTO CUNT_D_AUDITORIA (IDENTIFICACION,ID_USUARIO,FECHA,MODULO,TIPO,DESCRIPCION) 
    VALUES ($cedula,$id_usuario,SYSDATE,'$modulo','$tipo','$descripcion')";
    
    $select = oci_parse($db_conn, $query);
            
    if(oci_execute($select)){
        $result = 1;
    }else{
        $result = 0;
    }

    return $result;

}

?>