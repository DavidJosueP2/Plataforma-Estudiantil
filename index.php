<?php
include 'db.php';

$msg = '';
$base_url = 'http://localhost/pruebaCarpeta/';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos de ejemplo para completar la información faltante
    $nombre = "EjemploNombre";
    $apellido = "EjemploApellido";
    $user = mysqli_real_escape_string($connection, $_POST['user']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';

    if (preg_match($regex, $user)) {
        // Datos de ejemplo para completar la información faltante
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $activation = md5($user . time());

        // Datos de ejemplo para completar la información faltante
        $insertStmt = mysqli_prepare($connection, "INSERT INTO usuarios(nombre, apellido, email, contrasena, activacion) VALUES(?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insertStmt, 'sssss', $nombre, $apellido, $user, $hashedPassword, $activation);

        if (mysqli_stmt_execute($insertStmt)) {
            // Envío de correo aquí
            include 'smtp/Send_Mail.php';
            $to = $user;
            $subject = "Verificación de correo electrónico";
            $body = 'Hola, <br/><br/> Necesitamos asegurarnos de que eres humano. Por favor, verifica tu correo electrónico y comienza a usar tu cuenta en nuestro sitio web. <br/><br/> <a href="'.$base_url.'activation/'.$activation.'">'.$base_url.'activation/'.$activation.'</a>';

            // Verificar el resultado del envío de correo
            $sendMailResult = Send_Mail($to, $subject, $body);

            if ($sendMailResult === true) {
                $msg = "Registro exitoso. Por favor, activa tu correo electrónico.";
            } else {
                $msg = 'Error al enviar el correo: ' . $sendMailResult;
            }
        } else {
            $msg = 'Error al insertar en la base de datos: ' . mysqli_error($connection);
        }

        mysqli_stmt_close($insertStmt);
    } else {
        $msg = 'El correo electrónico ingresado no es válido. Por favor, inténtalo de nuevo.';
    }
}
echo $msg;
?>
