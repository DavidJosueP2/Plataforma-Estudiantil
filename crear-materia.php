<?php
include_once 'logic/GestorUsuario.php';
verificarSesionNoIniciada();

include_once 'configs.php';
$_GLOBALS['sectionHeader'] = "Crear Materia";

include_once 'crear-materia-process.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Facultad De Ingeniería En Sistemas, Electrónica E Industrial</title>
    <link rel="stylesheet" href="crear-materia-style.css" />
    <link rel="icon" href="imgs/selloFisei.ico" image/x-icon />
</head>

<body>
    <?php include 'nav-bar.php' ?>

    <!-- Contenido de la página -->
    <div class="content">
        <form action="" method="post">
            <h2>Creemos Una Materia</h2>
            <div class="form-group">
                <label for="nivel_paralelo">Selecciona un Nivel</label>
                <select id="nivel_paralelo" name="nivel_paralelo" required>
                    <?php echo obtenerOpcionesMenu(); ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nombre_materia">Nombre de la Materia</label>
                <input type="text" id="nombre_materia" name="nombre_materia" placeholder="Ingrese el nombre de la materia" required>
            </div>
            <div class="form-group submit-btn">
                <div class="submit-btn-container">
                    <input type="submit" value="Crear Materia" name="crear_materia">
                </div>
            </div>
            <label for="mensaje" id="mensaje">
                <?php 
                if (!isset($_POST['crear_materia'])) {
                    echo '';
                } else {
                    echo creaMateria(trim($_POST['nombre_materia']), $_POST['nivel_paralelo'], isset($_POST['crear_materia']));
                }
                ?>
            </label>
        </form>
    </div>
</body>

</html>