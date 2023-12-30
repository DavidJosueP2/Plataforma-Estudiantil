<?php
function ingresar($cedula, $nombre, $apellido, $email, $hashed_password, $activation, $status, $tipo_usuario)
{
    $urlRegistrar = 'http://192.168.1.4/BackEnd/registrar';
    $datos = array(
        'id' => $cedula,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'email' => $email,
        'password' => $hashed_password,
        'activation' => $activation,
        'status' => $status,
        'tipo' => $tipo_usuario
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlRegistrar);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
    curl_setopt($ch, CURLOPT_POST, true);

    $respuesta = curl_exec($ch);
    return $respuesta;
}
