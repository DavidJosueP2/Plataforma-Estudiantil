<?php
define('AUTHORIZED_SCRIPT', true);
include_once 'db.php';
$msg = '';

if (!empty($_GET['code']) && isset($_GET['code'])) {
    // Recoge el código de activación de la URL
    $code = mysqli_real_escape_string($connection, $_GET['code']);

    // Verifica si hay un usuario con ese código en la base de datos
    $urlComprobar = 'http://192.168.1.4/BackEnd/comprobarAct/' . $code;
    $jsonResponse = file_get_contents($urlComprobar);
    $data = json_decode($jsonResponse, true);
    //$c = mysqli_query($connection, "SELECT id_usuario, status FROM usuarios WHERE activacion='$code'");

    if (count($data) > 0) {
        foreach ($data as $user) {
            $userId = $user['id_usuario'];
            $userStatus = $user['status'];
        }
        //$userData = mysqli_fetch_assoc($c);
        //$userId = $userData['id_usuario'];
        //$userStatus = $userData['status'];

        if ($userStatus == '0') {
            // Si el usuario está inactivo, actualiza el estado a activo
            $urlActivar = 'http://192.168.1.4/BackEnd/activar/';
            $datos = array(
                'id' => $userId
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlActivar);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

            $respuesta = curl_exec($ch);
            curl_close($ch);
            //mysqli_query($connection, "UPDATE usuarios SET status='1' WHERE id_usuario='$userId'");
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