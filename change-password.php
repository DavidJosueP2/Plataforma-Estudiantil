<?php
include 'db.php';
date_default_timezone_set('America/Guayaquil'); // Establece la zona horaria a Ecuador Time (ECT)

// Al inicio del archivo change-password.php
$tokenFromURL = isset($_GET['token']) ? $_GET['token'] : null;
echo "Token de la URL: $tokenFromURL<br>";

function validarToken($token, $connection) {
    if (empty($token)) {
        echo "Error: No se ha proporcionado un token en la URL.<br>";
        return false;
    }

    $query = "SELECT id_usuario FROM usuarios WHERE reset_token=? AND reset_expiry > NOW()";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        // Si hay resultados, obtén la información del usuario
        $userData = mysqli_fetch_assoc($result);
        return $userData;
    } else {
        echo "Error: Token no válido o expirado.<br>";
        return false;
    }    
}

function cambiarContrasena($userId, $nuevaContrasena, $connection) {
    // Hash de la nueva contraseña antes de almacenarla en la base de datos
    $hashNuevaContrasena = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

    // Actualiza la contraseña en la base de datos para el usuario con el ID proporcionado
    $query = "UPDATE usuarios SET contrasena=?, reset_token=NULL, reset_expiry=NULL WHERE id_usuario=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "si", $hashNuevaContrasena, $userId);
    $result = mysqli_stmt_execute($stmt);

    // Verifica si la actualización fue exitosa
    return $result;
}

$msg = '';

// Verificar si se ha enviado el formulario de cambio de contraseña
if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
    // Obtener el token directamente del formulario
    $token = isset($_POST['token']) ? mysqli_real_escape_string($connection, $_POST['token']) : null;
    echo "Token del formulario: $token";

    $nuevaContrasena = $_POST['password'];
    $confirmarContrasena = $_POST['confirm_password'];

    // Verificar que las contraseñas sean iguales
    if ($nuevaContrasena !== $confirmarContrasena) {
        $msg = "Error: Las contraseñas no coinciden";
    } else {
        if ($token && $nuevaContrasena) {
            // Obtener la información del usuario asociado al token
            $userData = validarToken($token, $connection);
            if ($userData !== false) {
                $userId = $userData['id_usuario'];
                // Cambiar la contraseña y eliminar el token y la fecha de validación asociados
                if (cambiarContrasena($userId, $nuevaContrasena, $connection)) {
                    $msg = "¡Contraseña cambiada exitosamente!";
                    // Puedes redireccionar a una página de éxito o hacer lo que desees
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
