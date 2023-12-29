<?php
// Definir constante que indica que el script está autorizado
define('AUTHORIZED_SCRIPT', true);

include_once 'db.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Verificar que se han enviado los datos del formulario
  if (!empty($_POST['nombres']) && !empty($_POST['apellidos']) && !empty($_POST['cedula']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && isset($_POST['tipo_usuario'])) {

    // Obtener los valores del formulario


    $nombres = mysqli_real_escape_string($connection, $_POST['nombres']);
    $apellidos = mysqli_real_escape_string($connection, $_POST['apellidos']);
    $cedula = mysqli_real_escape_string($connection, $_POST['cedula']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);
    $tipo_usuario = mysqli_real_escape_string($connection, $_POST['tipo_usuario']);

    // Verificar restricciones de la cédula
    if (!preg_match('/^\d{10}$/', $_POST['cedula'])) {
      $msg = 'La cédula debe contener 10 dígitos numéricos.';
    } else {
      // Verificar que las contraseñas coincidan
      if ($_POST['password'] != $_POST['confirm_password']) {
        $msg = 'Las contraseñas no coinciden.';
      } else {
        // Verificar si la cédula ya ha sido utilizada
        $urlExistsCedula = 'http://192.168.1.4/BackEnd/comprobar/' . $cedula;
        $jsonResponse = file_get_contents($url);
        $data = json_decode($jsonResponse, true);

        if (count($data) > 0) {
          $msg = 'La cédula ya ha sido utilizada anteriormente.';
        } else {
          // Verificar formato de email
          $regex = '/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/';

          if (preg_match($regex, $email)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // encrypted password
            $activation = md5($email . time()); // encrypted email+timestamp

            // Insertar usuario en la base de datos
            //Se inserta datos mediante la API
            $urlRegistrar = 'http://192.168.1.4/CursoPHP2/registrar';
            $datos = array(
              'id' => $cedula,
              'nombre' => $nombre,
              'apellido' => $apellido,
              'email' => $email,
              'password' => $hashed_password,
              'activation' => $activation,
              'status' => '0',
              'tipo' => $tipo_usuario
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlRegistrar);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
            curl_setopt($ch, CURLOPT_POST, true);

            $respuesta = curl_exec($ch);
            //mysqli_query($connection, "INSERT INTO usuarios(id_usuario, nombre, apellido, email, contrasena, activacion, status, tipo_usuario) 
            //                                    VALUES('$cedula', '$nombres', '$apellidos', '$email', '$hashed_password', '$activation', '0', '$tipo_usuario')");

            // Enviar correo electrónico de verificación
            include 'smtp/Send_Mail.php';
            $to = $email;
            $subject = "Verificación de correo electrónico";
            $body = 'Hola, <br/> <br/> Necesitamos asegurarnos de que eres humano. Por favor, verifica tu correo electrónico y comienza a usar tu cuenta en nuestro sitio web. <br/> <br/> 
                                <a href="' . $base_url . 'activation/' . $activation . '">' . $base_url . 'activation/' . $activation . '</a>';

            Send_Mail($to, $subject, $body);
            $msg = "Registro exitoso, por favor, verifica tu correo electrónico.";
          } else {
            $msg = 'El correo electrónico ingresado no es válido. Por favor, inténtalo de nuevo.';
          }
        }
      }
    }
  } else {
    $msg = 'Todos los campos son obligatorios.';
  }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facultad De Ingeniería En Sistemas, Electrónica E Industrial</title>
  <link rel="stylesheet" href="register-style.css">
  <link rel="icon" href="imgs/selloFisei.ico" image/x-icon />
</head>

<body>
  <div class="wrapper">
    <h2>Crea una nueva cuenta</h2>
    <form action="register.php" method="post">
      <div class="input-box">
        <input type="text" name="nombres" placeholder="Ingresa tus nombres" required>
      </div>
      <div class="input-box">
        <input type="text" name="apellidos" placeholder="Ingresa tus apellidos" required>
      </div>
      <div class="input-box">
        <input type="text" name="cedula" placeholder="Ingresa tu número de cédula" required>
      </div>
      <div class="input-box">
        <input type="text" name="email" placeholder="Ingresa un email" required>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Crea una contraseña" required>
      </div>
      <div class="input-box">
        <input type="password" name="confirm_password" placeholder="Confirma contraseña" required>
      </div>
      <div class="radio-group">
        <label><input type="radio" name="tipo_usuario" value="estudiante" required> Para estudiante</label>
        <label><input type="radio" name="tipo_usuario" value="profesor" required> Para profesor</label>
      </div>
      <div class="input-box button">
        <input type="submit" value="Crear cuenta">
      </div>
      <div class="text">
        <h3>¿Ya tienes una cuenta? <a href="index.php">Ingresa ahora</a></h3>
      </div>
    </form>
    <?php echo $msg; ?>
  </div>
</body>

</html>