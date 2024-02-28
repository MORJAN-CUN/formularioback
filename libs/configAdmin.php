<?php
$user = "iceberg"; 
$password = "icebergpruebas";
$connection_strg = "172.16.1.80:1521/SIGPRUEB";
$db_conn = oci_connect($user, $password, $connection_strg,'AL32UTF8');

    if(!$db_conn) {
            die("No se pudo establecer la conexion");
        }