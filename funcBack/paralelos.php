<?php
function crearParalelo($datos,$ip){
    $url = $ip. 'paralels/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_POST, true);

    $respuesta = curl_exec($ch);
    curl_close($ch);
    $resultado = json_decode($respuesta,true);
    foreach ($resultado as $resultadito) {
        $res = $resultadito;
    }
    return $res;
}
?>