<?php
date_default_timezone_set('America/Bogota');

Class Correo{

    public static function getEstudiantes($db,$periodo){

        $query = "SELECT
        DISTINCT
            ord.PERIODO as Periodo
            ,ord.cliente_solicitado as Identificacion
            ,est.nom_largo as Nombre_Estudiante
            ,est.DIR_EMAIL as Email
            ,MAX(a.ULT_ACCESO) AS ULT_ACCESO
        FROM
                        orden ord
                        , liquidacion_orden liq
                        , ordenes_academico oac
                        , src_enc_liquidacion enc
                        , src_alum_programa alu
                        , bas_tercero est
                        , CUNT_ALUMNOS_ORDENES_X_PERIODO cun
                        , cunv_control_periodos per
                        , CUNT_HIS_GSUITE a
                    WHERE
                    ord.estado= 'V'
                    AND    liq.documento_originado =  ord.documento
                    AND    liq.organizacion_originado = ord.organizacion
                    AND    liq.orden = ord.orden
                    AND    oac.orden (+)= liq.orden
                    AND    oac.documento (+)= liq.documento_originado
                    AND    oac.organizacion (+)= liq.organizacion_originado
                    AND    enc.num_documento (+)= oac.referencia
                    AND    enc.cod_periodo (+)= oac.periodo
                    AND    alu.id_alum_programa (+)= enc.id_alum_programa
                    AND    est.num_identificacion (+)= to_char(ord.cliente_solicitado)
                    AND    to_char(cun.orden) = to_char(ord.orden)
                    AND    cun.documento = ord.documento
                    AND    a.IDENTIFICACION (+) = ord.cliente_solicitado
        AND    per.periodo= ord.periodo
                    AND    per.defecto ='S'
        AND ord.documento like 'F%'
        AND ord.organizacion =1
        AND cun.VAL_PAGADO>0
        AND ord.periodo NOT LIKE '%I%'
        AND est.DIR_EMAIL IS NOT NULL
        AND ord.periodo = '$periodo'
        GROUP BY
            ord.PERIODO
            ,ord.cliente_solicitado
            ,est.nom_largo
            ,est.DIR_EMAIL
        ORDER BY IDENTIFICACION";

        $select = oci_parse($db, $query);
                
        oci_execute($select);

        $result = array();

        while ($row = oci_fetch_array($select, OCI_ASSOC)) {
            array_push($result, $row);
        }

        return $result;


    }

    public function SinIngreso($db,$periodo){

    $query = "SELECT
    COUNT(DISTINCT est.DIR_EMAIL) TOTAL
    FROM
                    orden ord
                    , liquidacion_orden liq
                    , ordenes_academico oac
                    , src_enc_liquidacion enc
                    , src_alum_programa alu
                    , bas_tercero est
                    , CUNT_ALUMNOS_ORDENES_X_PERIODO cun
                    , cunv_control_periodos per
                    , CUNT_HIS_GSUITE a
                WHERE
                ord.estado= 'V'
                AND    liq.documento_originado =  ord.documento
                AND    liq.organizacion_originado = ord.organizacion
                AND    liq.orden = ord.orden
                AND    oac.orden (+)= liq.orden
                AND    oac.documento (+)= liq.documento_originado
                AND    oac.organizacion (+)= liq.organizacion_originado
                AND    enc.num_documento (+)= oac.referencia
                AND    enc.cod_periodo (+)= oac.periodo
                AND    alu.id_alum_programa (+)= enc.id_alum_programa
                AND    est.num_identificacion (+)= to_char(ord.cliente_solicitado)
                AND    to_char(cun.orden) = to_char(ord.orden)
                AND    cun.documento = ord.documento
                AND    a.IDENTIFICACION (+) = ord.cliente_solicitado
    AND    per.periodo= ord.periodo
                AND    per.defecto ='S'
    AND ord.documento like 'F%'
    AND ord.organizacion =1
    AND cun.VAL_PAGADO>0
    AND ord.periodo NOT LIKE '%I%'
    AND est.DIR_EMAIL IS NOT NULL
    AND ord.periodo = '$periodo'
    AND a.ULT_ACCESO = TO_DATE('1970-01-01', 'YYYY-MM-DD')";

        $select = oci_parse($db, $query);
                        
        oci_execute($select);

        while ($row = oci_fetch_array($select, OCI_ASSOC)) {
           $result = $row['TOTAL'];
        }

        return $result;

    }

    public function YaIngresaron($db,$periodo){

    $query = "SELECT
    COUNT( DISTINCT est.DIR_EMAIL) TOTAL
     FROM
                     orden ord
                     , liquidacion_orden liq
                     , ordenes_academico oac
                     , src_enc_liquidacion enc
                     , src_alum_programa alu
                     , bas_tercero est
                     , CUNT_ALUMNOS_ORDENES_X_PERIODO cun
                     , cunv_control_periodos per
                     , CUNT_HIS_GSUITE a
                 WHERE
                 ord.estado= 'V'
                 AND    liq.documento_originado =  ord.documento
                 AND    liq.organizacion_originado = ord.organizacion
                 AND    liq.orden = ord.orden
                 AND    oac.orden (+)= liq.orden
                 AND    oac.documento (+)= liq.documento_originado
                 AND    oac.organizacion (+)= liq.organizacion_originado
                 AND    enc.num_documento (+)= oac.referencia
                 AND    enc.cod_periodo (+)= oac.periodo
                 AND    alu.id_alum_programa (+)= enc.id_alum_programa
                 AND    est.num_identificacion (+)= to_char(ord.cliente_solicitado)
                 AND    to_char(cun.orden) = to_char(ord.orden)
                 AND    cun.documento = ord.documento
                 AND    a.IDENTIFICACION (+) = ord.cliente_solicitado
     AND    per.periodo= ord.periodo
                 AND    per.defecto ='S'
     AND ord.documento like 'F%'
     AND ord.organizacion =1
     AND cun.VAL_PAGADO>0
     AND ord.periodo NOT LIKE '%I%'
     AND est.DIR_EMAIL IS NOT NULL
     AND ord.periodo = '$periodo'
     AND (a.ULT_ACCESO <> TO_DATE('1970-01-01', 'YYYY-MM-DD') )";

        $select = oci_parse($db, $query);
                        
        oci_execute($select);

        while ($row = oci_fetch_array($select, OCI_ASSOC)) {
            $result = $row['TOTAL'];
        }

        return $result;

    }

    public function IngresoDesconocido($db,$periodo){

    $query = "SELECT
    COUNT( DISTINCT est.DIR_EMAIL) TOTAL
     FROM
                     orden ord
                     , liquidacion_orden liq
                     , ordenes_academico oac
                     , src_enc_liquidacion enc
                     , src_alum_programa alu
                     , bas_tercero est
                     , CUNT_ALUMNOS_ORDENES_X_PERIODO cun
                     , cunv_control_periodos per
                     , CUNT_HIS_GSUITE a
                 WHERE
                 ord.estado= 'V'
                 AND    liq.documento_originado =  ord.documento
                 AND    liq.organizacion_originado = ord.organizacion
                 AND    liq.orden = ord.orden
                 AND    oac.orden (+)= liq.orden
                 AND    oac.documento (+)= liq.documento_originado
                 AND    oac.organizacion (+)= liq.organizacion_originado
                 AND    enc.num_documento (+)= oac.referencia
                 AND    enc.cod_periodo (+)= oac.periodo
                 AND    alu.id_alum_programa (+)= enc.id_alum_programa
                 AND    est.num_identificacion (+)= to_char(ord.cliente_solicitado)
                 AND    to_char(cun.orden) = to_char(ord.orden)
                 AND    cun.documento = ord.documento
                 AND    a.IDENTIFICACION (+) = ord.cliente_solicitado
     AND    per.periodo= ord.periodo
                 AND    per.defecto ='S'
     AND ord.documento like 'F%'
     AND ord.organizacion =1
     AND cun.VAL_PAGADO>0
     AND ord.periodo NOT LIKE '%I%'
     AND est.DIR_EMAIL IS NOT NULL
     AND ord.periodo = '$periodo'
     AND (a.ULT_ACCESO IS NULL)";

        $select = oci_parse($db, $query);
                        
        oci_execute($select);

        while ($row = oci_fetch_array($select, OCI_ASSOC)) {
            $result = $row['TOTAL'];
        }

        return $result;

    }
        
}



?>