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
    $query = "SELECT id_usuario, nombre, apellido, tipo_usuario, email, contrasena, status FROM usuarios WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($connection, $query);
    
    if ($result) {
        // Verificar si se encontró un usuario
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            // Verificar si la cuenta está verificada (status = 1)
            if ($row['status'] == '1') {
                // Verificar la contraseña utilizando password_verify
                if (password_verify($password, $row['contrasena'])) {
                    // Iniciar sesión para el usuario
                    session_start();

                    $_SESSION['user_id'] = $row['id_usuario'];
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['name_user'] = $row['nombre'];
                    $_SESSION['last_name_user'] = $row['apellido'];
                    $_SESSION['user_type'] = $row['tipo_usuario'];
                                        
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
