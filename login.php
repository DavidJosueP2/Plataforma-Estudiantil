<?php
    include_once 'logic/GestorUsuario.php';
    verificarSesionIniciada();
$msg = '';
// Verificar si se ha enviado el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos (usando $connection en lugar de $con)
    define('AUTHORIZED_SCRIPT', true);
    require_once 'db.php';

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
        if (mysqli_num_rows($data) == 1) {

            // Verificar si la cuenta está verificada (status = 1)
            if ($data['status'] == '1') {
                // Verificar la contraseña utilizando password_verify
                if (password_verify($password, $data['contrasena'])) {
                    // Iniciar sesión para el usuario
                    session_start();

                    $_SESSION['user_id'] = $data['id_usuario'];
                    $_SESSION['user_email'] = $data['email'];
                    $_SESSION['name_user'] = $data['nombre'];
                    $_SESSION['last_name_user'] = $data['apellido'];
                    $_SESSION['user_type'] = $data['tipo_usuario'];
                                        
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
