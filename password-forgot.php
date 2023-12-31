<?php
include 'logic/GestorUsuario.php';
verificarSesionIniciada();

// Definir constante que indica que el script está autorizado
define('AUTHORIZED_SCRIPT', true);

require_once("db.php");
include_once 'configs.php';
include_once 'funcBack/token.php';
include_once 'funcBack/selectAll.php';
require_once 'smtp/Send_Mail.php';
date_default_timezone_set('America/Guayaquil'); // Establece la zona horaria a Ecuador Time (ECT)

$msg = "";

if (isset($_POST['enviar'])) {
  if (empty($_POST["email"])) {
    $msg = "Por favor, proporciona tu correo electrónico.";
  } else {
    $email = $_POST["email"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $msg = "Por favor, ingresa una dirección de correo electrónico válida.";
    } else {
      $datos = array(
        'email' => $email
      );
      $data = selectAllEmail($datos, $ip);
      //$query = "SELECT * FROM usuarios WHERE email = ?";
      //$stmt = mysqli_prepare($connection, $query);
      //mysqli_stmt_bind_param($stmt, "s", $email);
      //mysqli_stmt_execute($stmt);
      //mysqli_stmt_store_result($stmt);

      if ($data != null) {
        // Verificar si la cuenta está verificada (status = '1')
        foreach ($data as $datito) {
          $styatus = $datito['status'];
        }
        if ($styatus == '1') {
          $token = hash('sha256', bin2hex(random_bytes(32)));
          //echo "Token generado: $token";
          $expiryTimestamp = date('Y-m-d H:i:s', time() + 60 * 30);
          $datos = array(
            'reset_token' => $token,
            'reset_expiry' => $expiryTimestamp,
            'email' => $email
          );
          tokenReset($datos, $ip);
          // Cambia la siguiente línea para que apunte a "change-password.html" y pase el token como parámetro
          $subject = "Recuperación de Contraseña";
          $message = 'Hola, <br/> <br/>Haz clic en el siguiente enlace para restablecer tu contraseña:<br/> <br/>
                    <a href="' . $base_url . 'password-reset.php?token=' . $token . '">' . $base_url . 'password-reset.php?token=' . $token . '</a>';

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
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facultad De Ingeniería En Sistemas, Electrónica E Industrial</title>
  <link rel="stylesheet" href="password-forgot-style.css">
  <link rel="icon" href="imgs/selloFisei.ico" type="image/x-icon">
</head>

<body>
  <div class="wrapper">
    <label id="msg"><?php echo $msg ?></label>
    <h2>Recuperemos tu cuenta</h2>
    <form action="password-forgot.php" method="post">
      <div class="input-box">
        <label>Ingresa un correo para la recuperación:</label>
        <input type="text" name="email" placeholder="Email asociado" required>
      </div>
      <!-- Añadida mayor separación entre campos -->
      <div class="input-box button-box">
        <input type="submit" name="enviar" id="enviar" value="Enviar Correo">
      </div>
      <!-- Mayor separación añadida aquí -->
      <div class="text">
        <h3><a href="index.php">Volver atrás</a></h3>
      </div>
    </form>
  </div>
</body>

</html>