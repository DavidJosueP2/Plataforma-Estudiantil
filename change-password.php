<?php
include 'funcBack/selectAll.php';
include 'funcBack/token.php';
date_default_timezone_set('America/Guayaquil');

$msg = '';

function validarToken($token) {
    //Se incluye la variable global de la ip
    include 'configs.php';
    if (empty($token)) {
        echo "Error: No se ha proporcionado un token en el formulario.<br>";
        return false;
    }
    //Se ponen los datos en un array
    $datos = array(
        'reset_token'=>$token
    );
    $result = selectIdClave($datos,$ip);
    //Se retorna el id si sale bien, se retorna falso si sale mal
    if ($result != null) {
        foreach ($result as $resultito) {
            $id = $resultito['id_usuario'];
        }
        return $id;
    } else {
        echo "Error: Token no válido o expirado.<br>";
        return false;
    }    
}

function cambiarContrasena($userId, $nuevaContrasena) {
    //Se incluye la variable global de la ip
    include 'configs.php';
    $hashNuevaContrasena = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
    //Se ponen los datos en un array
    $datos = array(
        'contrasena'=>$hashNuevaContrasena,
        'id'=>$userId
    );
    $result = cambiaClave($datos,$ip);
    //Se retornan los datos que se tengan
    return $result;
}

if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
    $token = isset($_POST['token']) ? $_POST['token'] : null;
    echo "Token de formualrio: $token<br>";
    
    $nuevaContrasena = $_POST['password'];
    $confirmarContrasena = $_POST['confirm_password'];

    if ($nuevaContrasena !== $confirmarContrasena) {
        $msg = "Error: Las contraseñas no coinciden";
    } else {
        if ($token && $nuevaContrasena) {
            $userId = validarToken($token);
            if ($userId !== false) {
                if (cambiarContrasena($userId, $nuevaContrasena)) {
                    $msg = "¡Contraseña cambiada exitosamente!";
                } else {
                    $msg = "Error: No se pudo cambiar la contraseña";
                }
            }
        } else {
            $msg = "Error: Datos insuficientes para cambiar la contraseña";
        }
    }
}

echo $msg;
?>
