<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambia tu contraseña</title>
    <link rel="stylesheet" href="change-password-style.css">
    <link rel="icon" href="imgs/selloFisei.ico" image/x-icon />
</head>
<body>
    <div class="wrapper">
        <h2>Cambia tu contraseña</h2>
        <form action="change-password.php" method="post">
            <div class="input-box">
                <input type="password" name="password" placeholder="Nueva contraseña" required>
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Confirma contraseña" required>
            </div>
            <!-- Agrega un campo oculto para enviar el token -->
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
            <div class="input-box button">
                <input type="submit" value="Cambiar Contraseña">
            </div>
            <div class="text">
                <h3>¿Ya tienes una cuenta? <a href="index.html">Ingresa ahora</a></h3>
            </div>
        </form>
    </div>
</body>
</html>
