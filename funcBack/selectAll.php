<?php
function selectAllEmail($datos,$ip)
{
    $url = $ip .'seleccionar/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_POST, true);

    $respuesta = curl_exec($ch);
    curl_close($ch);
    return json_decode($respuesta,true);
}

function selectAllID($datos,$ip)
{
    $url = $ip .'select/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_POST, true);

    $respuesta = curl_exec($ch);
    curl_close($ch);
    return json_decode($respuesta,true);
}

function selectIdClave($datos,$ip) {
    $url = $ip.'selectToken/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_POST, true);

    $respuesta = curl_exec($ch);
    curl_close($ch);
    return json_decode($respuesta,true);
}

function selectRestrictedID($datos,$ip)
{
    $url = $ip .'selectRestricted/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_POST, true);

    $respuesta = curl_exec($ch);
    curl_close($ch);
    return json_decode($respuesta,true);
}
?>