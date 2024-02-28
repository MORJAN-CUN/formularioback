<?php
$user = "ICEBERG"; 
$password = "t3zsjuvGee";
$connection_strg = "172.16.1.175:1521/sig";
$db_conn = oci_connect($user, $password, $connection_strg,'AL32UTF8');

    if(!$db_conn) {
            die("No se pudo establecer la conexion");
    }
