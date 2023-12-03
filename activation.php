<?php
include 'db.php';
$msg = '';

if (!empty($_GET['code']) && isset($_GET['code'])) {
    // Recoge el código de activación de la URL
    $code = mysqli_real_escape_string($connection, $_GET['code']);

    // Verifica si hay un usuario con ese código en la base de datos
    $c = mysqli_query($connection, "SELECT id_usuario, status FROM usuarios WHERE activacion='$code'");

    if (mysqli_num_rows($c) > 0) {
        $userData = mysqli_fetch_assoc($c);
        $userId = $userData['id_usuario'];
        $userStatus = $userData['status'];

        if ($userStatus == '0') {
            // Si el usuario está inactivo, actualiza el estado a activo
            mysqli_query($connection, "UPDATE usuarios SET status='1' WHERE id_usuario='$userId'");
            $msg = "Tu cuenta ha sido activada";
        } else {
            $msg = "Tu cuenta ya está activa, no es necesario activarla nuevamente";
        }
    } else {
        $msg = "Código de activación incorrecto";
    }
}
?>
<!-- Parte HTML para mostrar el mensaje -->
<?php echo $msg; ?>
