<?php
require_once("db.php");
require 'smtp/Send_Mail.php';
date_default_timezone_set('America/Guayaquil'); // Establece la zona horaria a Ecuador Time (ECT)

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $msg = "Por favor, proporciona tu correo electrónico.";
    } else {
        $email = mysqli_real_escape_string($connection, $_POST["email"]);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = "Por favor, ingresa una dirección de correo electrónico válida.";
        } else {
            $query = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Obtener el estado (status) de la cuenta
                mysqli_stmt_bind_result($stmt, $id, $nombre, $apellido, $email, $contrasena, $activacion, $status, $fecha_registro, $tipo_usuario, $reset_token, $reset_expiry);
                mysqli_stmt_fetch($stmt);

                // Verificar si la cuenta está verificada (status = '1')
                if ($status === '1') {
                    $token = hash('sha256', bin2hex(random_bytes(32)));
                    echo "Token generado: $token";

                    $expiryTimestamp = date('Y-m-d H:i:s', time() + 60 * 30);

                    $updateQuery = "UPDATE usuarios SET reset_token = ?, reset_expiry = ? WHERE email = ?";
                    $updateStmt = mysqli_prepare($connection, $updateQuery);
                    mysqli_stmt_bind_param($updateStmt, "sss", $token, $expiryTimestamp, $email);
                    mysqli_stmt_execute($updateStmt);

                    // Cambia la siguiente línea para que apunte a "change-password.html" y pase el token como parámetro
                    $subject = "Recuperación de Contraseña";
                    $message = 'Hola, <br/> <br/>Haz clic en el siguiente enlace para restablecer tu contraseña:<br/> <br/>
                    <a href="' . $base_url . 'password-reset.php?token=' . $token . '">' . $base_url . 'password-reset.html?token=' . $token . '</a>';
                    // Uso de la función Send_Mail() para enviar el correo electrónico
                    $result = Send_Mail($email, $subject, $message);

                    if ($result === true) {
                        $msg = "Se ha enviado un correo electrónico con instrucciones para restablecer tu contraseña.";
                    } else {
                        $msg = "Error en el envío del correo: $result";
                    }
                } else {
                    $msg = "La cuenta no está verificada. No se puede solicitar el restablecimiento de contraseña.";
                }

            } else {
                $msg = "No se encontró ninguna cuenta asociada a ese correo electrónico.";
            }

            mysqli_stmt_close($stmt);
            mysqli_stmt_close($updateStmt);
        }
    }
}

echo $msg;
?>
