<?php
$id_materia = $_GET['m'];
$id_paralelo = $_GET['p'];
$nombre_nivel = $_GET['n'];
$nombre_materia = $_GET['n_m'];
$nombre_paralelo = $_GET['n_p'];
include_once 'logic/GestorUsuario.php';
verificarSesionNoIniciada();

include_once 'configs.php';
$_GLOBALS['sectionHeader'] = $nombre_nivel.'-'.$nombre_materia.'-'.$nombre_paralelo;
include_once 'funcBack/cursos.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Facultad De Ingeniería En Sistemas, Electrónica E Industrial</title>
    <link rel="stylesheet" href="cursos-estudiante-style.css" />
    <link rel="icon" href="imgs/selloFisei.ico" image/x-icon />
</head>

<body>
    <?php
    include 'nav-bar.php';
    ?>
</body>
</html>