<?php
function selectAll($datos,$ip)
{
    $urlRegistrar = $ip .'seleccionar/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlRegistrar);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_POST, true);

    $respuesta = curl_exec($ch);
    curl_close($ch);
    return json_decode($respuesta);
}
?>