<?php
function activar($datos, $ip){
    $urlActivar = $ip.'activar/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlActivar);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($datos));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    $respuesta = curl_exec($ch);
    curl_close($ch);
    return $respuesta;
}
?>