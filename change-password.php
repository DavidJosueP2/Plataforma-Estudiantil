<?php
date_default_timezone_set('America/Guayaquil');

$msg = '';

function validarToken($token) {
    include_once 'configs.php';
    include_once 'funcBack/token.php';
    if (empty($token)) {
        echo "Error: No se ha proporcionado un token en el formulario.<br>";
        return false;
    }

    $datos = array(
        'reset_token'=>$token
    );

    $result = selectIdClave($datos,$ip);
    //$query = "SELECT id_usuario FROM usuarios WHERE reset_token=? AND reset_expiry > NOW()";
    //$stmt = mysqli_prepare($connection, $query);
    //mysqli_stmt_bind_param($stmt, "s", $token);
    //mysqli_stmt_execute($stmt);
    //$result = mysqli_stmt_get_result($stmt);

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
    include_once 'configs.php';
    include_once 'funcBack/selectAll.php';
    $hashNuevaContrasena = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
    $datos = array(
        'contrasena'=>$hashNuevaContrasena,
        'id'=>$userId
    );
    $result = cambiaClave($datos,$ip);
    /*$query = "UPDATE usuarios SET contrasena=?, reset_token=NULL, reset_expiry=NULL WHERE id_usuario=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "si", $hashNuevaContrasena, $userId);
    $result = mysqli_stmt_execute($stmt)*/;

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
