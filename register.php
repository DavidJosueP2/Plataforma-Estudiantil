<?php
include 'db.php';
$msg = '';

if (!empty($_POST['nombres']) && !empty($_POST['apellidos']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && isset($_POST['tipo_usuario'])) {
    // username and password sent from form
    $nombres = mysqli_real_escape_string($connection, $_POST['nombres']);
    $apellidos = mysqli_real_escape_string($connection, $_POST['apellidos']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);
    $tipo_usuario = mysqli_real_escape_string($connection, $_POST['tipo_usuario']);

    // Verificar que las contraseñas coincidan
    if ($password != $confirm_password) {
        $msg = 'Las contraseñas no coinciden.';
    } else {
        // regular expression for email check
        $regex = '/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/';

        if (preg_match($regex, $email)) {
            $password = md5($password); // encrypted password
            $activation = md5($email . time()); // encrypted email+timestamp
            $count = mysqli_query($connection, "SELECT id_usuario FROM usuarios WHERE email='$email'");

            // email check
            if (mysqli_num_rows($count) < 1) {
                mysqli_query($connection, "INSERT INTO usuarios(nombre, apellido, email, contrasena, activacion, status, tipo_usuario) 
                                        VALUES('$nombres', '$apellidos', '$email', '$password', '$activation', '0', '$tipo_usuario')");

                // sending email
                include 'smtp/Send_Mail.php';
                $to = $email;
                $subject = "Verificación de correo electrónico";
                $body = 'Hola, <br/> <br/> Necesitamos asegurarnos de que eres humano. Por favor, verifica tu correo electrónico y comienza a usar tu cuenta en nuestro sitio web. <br/> <br/> 
                        <a href="' . $base_url . 'activation/' . $activation . '">' . $base_url . 'activation/' . $activation . '</a>';

                Send_Mail($to, $subject, $body);
                $msg = "Registro exitoso, por favor, verifica tu correo electrónico.";
            } else {
                $msg = 'El correo electrónico ya está en uso. Por favor, utiliza otro.';
            }
        } else {
            $msg = 'El correo electrónico ingresado no es válido. Por favor, inténtalo de nuevo.';
        }
    }
}

echo $msg;

?>

