<?php

class RpviFama{

    public static function consultaRpviFama($db,$identificacion,$periodo){
		$responseFama = [];
		$responseRPVI = [];
		$response = [];

		//FAMA

		$queryFama = "SELECT 
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
						    ,ORD.valor_total
						FROM ORDEN ORD
						INNER JOIN detalle_orden DET ON ORD.ORDEN=DET.ORDEN AND ORD.DOCUMENTO=DET.DOCUMENTO
						INNER JOIN v_cliente CLI ON ord.cliente_solicitado=CLI.cliente
						INNER JOIN ordenes_academico ordaca ON  ordaca.documento  = ord.documento AND ordaca.orden  = ord.orden AND ordaca.organizacion  = ord.organizacion
						INNER JOIN src_alum_programa pro ON  pro.id_alum_programa  = ordaca.id_alum_programa      
						INNER JOIN src_uni_academica uni ON  uni.cod_unidad = pro.cod_unidad
						INNER JOIN bas_dependencia dep ON dep.id_dependencia = uni.id_dependencia
						INNER JOIN cunv_ciclos ci on uni.niv_formacion=ci.cod_ciclo
						WHERE ORD.ESTADO='V' AND ord.cliente_solicitado IN ('$identificacion')  AND ORD.PERIODO='$periodo'";

		$selectFama = oci_parse($db, $queryFama);
    	
    	oci_execute($selectFama);

    	while($x = oci_fetch_array($selectFama,OCI_BOTH)){
    		$data = ['documento' => $x['DOCUMENTO'],
    				 'orden' => $x['ORDEN'],
    				 'periodo' => $x['PERIODO'],
    				 'identificacion' => $x['CLIENTE_SOLICITADO'],
    				 'usuario' => $x['NOMBRE_NEGOCIO'],
    				 'modalidad' => $x['NOM_MODALIDAD'],
    				 'programa' => $x['NOM_PROGRAMA'],
    				 'ciclo' => $x['CICLO']
    				];

    		array_push($responseFama, $data);		 
    	}

    	//RPVI

    	$queryRPVI = "SELECT * FROM ORDEN 
					  WHERE PERIODO  = '$periodo'
					  AND CLIENTE_SOLICITADO = '$identificacion'
					  AND DOCUMENTO  = 'RPVI'";

		$selectRPVI = oci_parse($db, $queryRPVI);
    	
    	oci_execute($selectRPVI);

    	while($y = oci_fetch_array($selectRPVI,OCI_BOTH)){
    		$data = ['documento' => $y['DOCUMENTO'],
    				 'orden' => $y['ORDEN'],
    				 'periodo' => $y['PERIODO'],
    				 'identificacion' => $y['CLIENTE_SOLICITADO'],
    				 'descripcion' => $y['DESCRIPCION']
    				];

    		array_push($responseRPVI, $data);		 
    	}

    	array_push($response, $responseFama);
    	array_push($response, $responseRPVI);

    	return $response;
		
	}

}