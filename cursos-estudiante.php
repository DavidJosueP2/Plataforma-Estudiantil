<?php
include_once 'logic/GestorUsuario.php';
verificarSesionNoIniciada();

include_once 'configs.php';
$_GLOBALS['sectionHeader'] = "Tus cursos";
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
    <div class="cursos-estudiante">
        <label for="cursos" class="cursos" id="cursos">Cursos del estudiante</label>
        <?php
        $id_estudiante = $_SESSION['user_id'];
        $datos = array(
            'id_estudiante'=>$id_estudiante
        );
        $resultado = getCursosRestricted($datos,$ip);
        foreach ($resultado as $resultadito) {
            $id_materia = $resultadito['id_materia'];
            $id_paralelo = $resultadito['id_paralelo'];
            $nombre_nivel = $resultadito['nombre_nivel'];
            $nombre_materia = $resultadito['nombre_materia'];
            $nombre_paralelo = $resultadito['nombre_paralelo'];
            echo "<a href='curso-estudiante.php?m=$id_materia&p=$id_paralelo&n=$nombre_nivel&n_m=$nombre_materia&n_p=$nombre_paralelo'>".$nombre_nivel.'-'.$nombre_materia.'-'.$nombre_paralelo."</a></br>";
        }
        ?>
    </div>
</body>
</html>