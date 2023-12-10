<?php
include 'db.php';
date_default_timezone_set('America/Guayaquil');

$msg = '';

function validarToken($token, $connection) {
    if (empty($token)) {
        echo "Error: No se ha proporcionado un token en el formulario.<br>";
        return false;
    }

    $query = "SELECT id_usuario FROM usuarios WHERE reset_token=? AND reset_expiry > NOW()";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        return $userData;
    } else {
        echo "Error: Token no válido o expirado.<br>";
        return false;
    }    
}

function cambiarContrasena($userId, $nuevaContrasena, $connection) {
    $hashNuevaContrasena = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
    $query = "UPDATE usuarios SET contrasena=?, reset_token=NULL, reset_expiry=NULL WHERE id_usuario=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "si", $hashNuevaContrasena, $userId);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
    $token = isset($_POST['token']) ? mysqli_real_escape_string($connection, $_POST['token']) : null;
    echo "Token de formualrio: $token<br>";
    
    $nuevaContrasena = $_POST['password'];
    $confirmarContrasena = $_POST['confirm_password'];

    if ($nuevaContrasena !== $confirmarContrasena) {
        $msg = "Error: Las contraseñas no coinciden";
    } else {
        if ($token && $nuevaContrasena) {
            $userData = validarToken($token, $connection);
            if ($userData !== false) {
                $userId = $userData['id_usuario'];
                if (cambiarContrasena($userId, $nuevaContrasena, $connection)) {
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
