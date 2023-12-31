<?php
define('AUTHORIZED_SCRIPT', true);
include_once 'funcBack/activar.php';
include_once 'configs.php';
$msg = '';

if (!empty($_GET['code']) && isset($_GET['code'])) {
    // Recoge el código de activación de la URL
    $code = $_GET['code'];

    // Verifica si hay un usuario con ese código en la base de datos
    $urlComprobar = $ip.'comprobarAct/' . $code;
    $jsonResponse = file_get_contents($urlComprobar);
    $data = json_decode($jsonResponse, true);
    if (count($data) > 0) {
        foreach ($data as $user) {
            $userId = $user['id_usuario'];
            $userStatus = $user['status'];
        }
        if ($userStatus == '0') {
            // Si el usuario está inactivo, actualiza el estado a activo
            $datos = array(
                'id' => $userId
            );
            $respuesta=activar($datos,$ip);
            $msg = json_decode($respuesta);
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