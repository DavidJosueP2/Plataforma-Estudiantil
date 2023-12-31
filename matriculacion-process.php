
<?php
session_start();

include_once 'logic/GestorUsuario.php';
verificarSesionNoIniciada();
$_GLOBALS['sectionHeader'] = "Matriculación";

define('AUTHORIZED_SCRIPT', true);
$esProfesor = ($_SESSION['user_type'] == "profesor");
$msg = '';

//Formulario para vincular a un Docente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['vincular'])) {
    $msg = '';
    $msg = vincularDocente();
    echo $msg;
}

//Formulario para matricular a un estudiante
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['matricular'])) {
    $msg = '';
    if (!isset($_POST['confirmacionMatricula'])) {
        $msg = "Debes confirmar la matriculación seleccionando el checkbox.";
    } else {
        if ($_SESSION['user_type'] == 'estudiante') {
            $msg = matricularEstudiante();
        } else {
            $msg = "Error: Acción no permitida para el usuario actual.";
        }
    }

    echo $msg;
}

// Función para matricular a un estudiante
function matricularEstudiante() {
    // Obtener datos del formulario
    $idMateria = $_POST['materia'];
    $idParalelo = $_POST['paralelo'];
    $idEstudiante = $_SESSION['user_id']; // Obtener el ID del estudiante de la sesión

    // Verificar si ya existe un registro para la materia y paralelo seleccionados
    $idMateriaParalelo = obtenerIdMateriaParalelo($idMateria, $idParalelo);

    // Obtener el ID del profesor por defecto
    //$idProfesorPorDefecto = "1877283881";
    if (!is_null($idMateriaParalelo)) {
        // Ya existe un registro para esta materia y paralelo, mostrar mensaje
        $msg = "Ya estás inscrito en esta materia y paralelo.";
    } else {
        // No existe un registro, crear uno nuevo
        $idMateriaParalelo = crearMateriaParalelo($idMateria, $idParalelo);

        if (!is_null($idMateriaParalelo)) {
            // Matriculación exitosa
            $msg = "Matriculación exitosa. Ahora estás inscrito en la materia.";

            // Inscribir al estudiante en la materia_paralelo
            inscribirEstudiante($idMateriaParalelo, $idEstudiante);
        } else {
            // Error al matricular
            $msg = "Error al matricular en la materia. Inténtalo de nuevo.";
        }
    }

    return $msg; // Devolver el mensaje de matriculación
}

// Función para inscribir al estudiante en la materia_paralelo
function inscribirEstudiante($idMateriaParalelo, $idEstudiante) {
    include 'configs.php';
    include_once 'funcBack/inscripciones.php';
    $datos = array(
        'id_materia_paralelo'=>$idMateriaParalelo,
        'id_usuario'=>$idEstudiante
    );
    $respuesta = inscribeEstudiante($datos,$ip);
    return $respuesta;
}

function obtenerIdMateriaParalelo($idMateria, $idParalelo) {
    include 'configs.php';
    include_once 'funcBack/inscripciones.php';
    $datos = array(
        'id_materia'=>$idMateria,
        'id_paralelo'=>$idParalelo
    );
    $idMateriaParalelo = selectMateriaParalelo($datos,$ip);
    return $idMateriaParalelo;
}

function crearMateriaParalelo($idMateria, $idParalelo) {
    include 'configs.php';
    include_once 'funcBack/inscripciones.php';
    $datos = array(
        'id_materia'=>$idMateria,
        'id_paralelo'=>$idParalelo,
    );
    $resultado = crearCurso($datos,$ip);
    return $resultado;
}

function vincularDocente() {
    $idMateria = $_POST['materia'];
    $idParalelo = $_POST['paralelo'];
    $idDocente = $_POST['profesor'];

    $idMateriaParalelo = obtenerIdMateriaParalelo($idMateria, $idParalelo);

    if (is_null($idMateriaParalelo)) {
        $idMateriaParalelo = crearMateriaParalelo($idMateria, $idParalelo, $idDocente);
    } else {
        $msg = actualizarMateriaParalelo($idMateriaParalelo, $idDocente);
    }

    return $msg;
}

function actualizarMateriaParalelo($idMateriaParalelo, $idDocente) {
    include 'configs.php';
    include_once 'funcBack/inscripciones.php';
    $datos = array(
        'id_curso'=>$idMateriaParalelo,
        'id_docente'=>$idDocente
    );
    $res = actualizaDocente($datos,$ip);
    foreach ($res as $resultado) {
        $respuesta = $resultado;
    }
    return $respuesta;
}

?>

