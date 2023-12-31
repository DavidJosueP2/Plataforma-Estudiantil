<?php
// Iniciar sesión del usuario
session_start();

include_once 'logic/GestorUsuario.php';
verificarSesionNoIniciada();

include_once 'configs.php';
$_GLOBALS['sectionHeader'] = "Matriculación";

define('AUTHORIZED_SCRIPT', true);
include_once 'db.php';
// Comprueba sesión
$esProfesor = ($_SESSION['user_type'] == "profesor");

// Inicializar las variables paralelos y materias
$paralelos = array();
$materias = array();
$msg = 'empty';

// Función para obtener opciones del nivel
function obtenerOpcionesNivel() {
    include 'configs.php';
    $urlGet = $ip.'niveles/';
    $jsonResponse = file_get_contents($urlGet);
    $data = json_decode($jsonResponse,true);
    /*global $connection; // Acceder a la conexión desde db.php

    // Consulta preparada para obtener solo los niveles desde la base de datos
    $sql = "SELECT nombre_nivel FROM niveles";
    $stmt = mysqli_prepare($connection, $sql);*/
    if ($data != null) {
        //mysqli_stmt_execute($stmt);
        //$result = mysqli_stmt_get_result($stmt);

        $opcionesHTML = '<option value="" selected disabled>Seleccione Nivel</option>';
        foreach ($data as $nivel) {
            $opcionesHTML .= "<option value='{$nivel['nombre_nivel']}'>{$nivel['nombre_nivel']}</option>";
        }
        
        /*while ($row = mysqli_fetch_assoc($result)) {
            $opcionesHTML .= "<option value='{$row['nombre_nivel']}'>{$row['nombre_nivel']}</option>";
        }

        mysqli_stmt_close($stmt);*/

        return $opcionesHTML;
    } else {
        // Manejar el caso en que la preparación de la consulta falle
        return '<option value="" selected disabled>Error al obtener niveles</option>';
    }
}

// Función para obtener paralelos según el nivel seleccionado
function obtenerParalelosPorNivel($nivel) {
    global $connection;

    $sql = "SELECT id_paralelo, nombre_paralelo FROM paralelos WHERE id_nivel = (SELECT id_nivel FROM niveles WHERE nombre_nivel = ?)";
    
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $nivel);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $paralelos = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $paralelos[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $paralelos;
}

// Función para obtener materias según el nivel seleccionado
function obtenerMateriasPorNivel($nivel) {
    global $connection;

    $sql = "SELECT id_materia, nombre_materia FROM materias WHERE id_nivel = (SELECT id_nivel FROM niveles WHERE nombre_nivel = ?)";
    
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $nivel);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $materias = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $materias[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $materias;
}

//Funcion para obtener los profesores
function obtenerOpcionesProfesor() {
    global $connection;

    $sql = "SELECT id_usuario, CONCAT(apellido, ' ', nombre) AS nombre_completo FROM usuarios WHERE tipo_usuario = 'profesor' ORDER BY apellido";

    $result = mysqli_query($connection, $sql);

    $profesores = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $profesores[] = $row;
    }

    return $profesores;
}

//Despliega como opciones un array
function mostrarOpciones($datos, $idCampo, $nombreCampo) {
    echo "<select id='$idCampo' name='$nombreCampo' required>";
    echo "<option value='' selected disabled>Selecciona una opción</option>";
    foreach ($datos as $dato) {
        echo "<option value='{$dato['id_usuario']}'>{$dato['nombre_completo']}</option>";
    }
    echo "</select>";
}

// Obtener paralelos y materias para llenar los selects
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nivel'])) {
    $nivelSeleccionado = $_POST['nivel'];

    // Incluir la lógica para obtener paralelos y materias según el nivel seleccionado
    if (isset($_POST['obtenerParalelos']) && $_POST['obtenerParalelos']) {
        $paralelos = obtenerParalelosPorNivel($nivelSeleccionado);
    }

    if (isset($_POST['obtenerMaterias']) && $_POST['obtenerMaterias']) {
        $materias = obtenerMateriasPorNivel($nivelSeleccionado);
    }

    // Enviar la respuesta JSON con los datos
    echo json_encode(['paralelos' => $paralelos, 'materias' => $materias]);
    exit;
}

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
        <form action="matriculacion-process.php" method="post">
            <h2>
            <?php  
                if ($_SESSION['user_type'] == "profesor") {
                    echo "Vincular a una materia";
                } elseif ($_SESSION['user_type'] == "estudiante") {
                    echo "Matricularme en una materia";
                }
            ?>    
            </h2>
            <div class="form-group">
                <label for="nivel">Selecciona un Nivel</label>
                <select id="nivel" name="nivel" required>
                    <?php echo obtenerOpcionesNivel(); ?>
                </select>
            </div>
            <div class="form-group">
                <label for="paralelo">Selecciona un Paralelo</label>
                <select id="paralelo" name="paralelo" required>
                    <!-- El contenido se llenará dinámicamente mediante AJAX -->
                </select>
            </div>
            <div class="form-group">
                <label for="materia">Selecciona una Materia</label>
                <select id="materia" name="materia" required>
                    <!-- El contenido se llenará dinámicamente mediante AJAX -->
                </select>
            </div>
            <?php
                echo '<label for="profesor">Selecciona un Profesor</label>';
                if ($esProfesor) {
                    $profesores = obtenerOpcionesProfesor();
                    mostrarOpciones($profesores, 'profesor', 'profesor');
                }
            ?>
            <div class="form-group checkbox-container">
                <input type="checkbox" id="confirmacionMatricula" name="confirmacionMatricula" required>
                <?php
                    if ($_SESSION['user_type'] == "profesor") {
                        echo '<label for="confirmacionMatricula">Estoy seguro de esta vinculación</label>';
                    } elseif ($_SESSION['user_type'] == "estudiante") {
                        echo '<label for="confirmacionMatricula">Estoy seguro de matricularme en esta materia</label>';
                    }
                ?>
            </div>
            <div class="form-group submit-btn">
                <div class="submit-btn-container">
                    <input type="submit" value="<?php echo ($_SESSION['user_type'] == "profesor") ? 'Vincular' : 'Matricularme'; ?>" name="<?php echo ($_SESSION['user_type'] == "profesor") ? 'vincular' : 'matricular'; ?>">
                </div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="matriculacion.js"></script>
</body>
</html>

