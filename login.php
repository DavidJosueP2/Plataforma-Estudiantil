<?php
    include_once 'logic/GestorUsuario.php';
    verificarSesionIniciada();
$msg = '';
// Verificar si se ha enviado el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos (usando $connection en lugar de $con)
    define('AUTHORIZED_SCRIPT', true);

    // Obtener datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consultar la base de datos para el usuario con el correo proporcionado
    //Se consulta mediante la url

    $url = 'http://192.168.1.4/BackEnd/verificar/'.$email;
    
    $jsonResponse = file_get_contents($url);
    $data = json_decode($jsonResponse, true);
    
    if ($data) {
        // Verificar si se encontró un usuario
        if (count($data) == 1) {
            foreach ($data as $usuario) {
                $user_id = $usuario['id_usuario'];
                $user_email = $usuario['email'];
                $contrasena = $usuario['contrasena'];
                $name_user = $usuario['nombre'];
                $last_name_user = $usuario['apellido'];
                $user_type = $usuario['tipo_usuario'];
                $status = $usuario['status'];
            }
            // Verificar si la cuenta está verificada (status = 1)
            if ($status == '1') {
                // Verificar la contraseña utilizando password_verify
                if (password_verify($password, $contrasena)) {
                    // Iniciar sesión para el usuario
                    session_start();

                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_email'] = $user_email;
                    $_SESSION['name_user'] = $name_user;
                    $_SESSION['last_name_user'] = $last_name_user;
                    $_SESSION['user_type'] = $user_type;
                                        
                    // Redirigir al usuario a home.php con el id_usuario como parámetro
                    header("Location: home.php");
                    exit();
                } else {
                    $msg = "Contraseña incorrecta";
                }
            } else {
                $msg = "La cuenta no está verificada. Por favor, verifica tu cuenta.";
            }
        } else {
            $msg = "Correo electrónico no registrado";
        }
    } else {
        $msg = "Error al consultar la base de datos";
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($connection);
}
echo $msg;
?>
