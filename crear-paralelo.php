<?php
include_once 'logic/GestorUsuario.php';
verificarSesionNoIniciada();

include_once 'configs.php';
$_GLOBALS['sectionHeader'] = "Crear Paralelo";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Facultad De Ingeniería En Sistemas, Electrónica E Industrial</title>
    <link rel="stylesheet" href="crear-paralelo-style.css" />
    <link rel="icon" href="imgs/selloFisei.ico" image/x-icon />
    <script src="crear-paralelo.js"></script>
</head>

<body>
    <?php 
    include 'nav-bar.php';
    include_once 'crear-paralelo-process.php';
    ?>

    <!-- Contenido de la página -->
    <div class="content">
        <form action="" method="post">
            <h2>Creemos Un Paralelo</h2>
            <div class="form-group">
                <label for="nivel">Selecciona un Nivel</label>
                <select id="nivel" name="nivel" required>
                    <?php echo getNiveles(); ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nombre_paralelo">Nombre del Paralelo</label>
                <input type="text" id="nombre_paralelo" name="nombre_paralelo" placeholder="Ingrese el nombre del paralelo" required>
            </div>
            <div class="form-group submit-btn">
                <div class="submit-btn-container">
                    <input type="submit" value="Crear Paralelo" name="crear_paralelo">
                </div>
            </div>
            <label for="mensaje" id="mensaje">
                <?php 
                if (!isset($_POST['crear_paralelo'])) {
                    echo '';
                } else {
                    echo creaParalelo(trim($_POST['nombre_paralelo']), $_POST['nivel']);
                }
                ?>
            </label>
        </form>
    </div>
</body>
</html>