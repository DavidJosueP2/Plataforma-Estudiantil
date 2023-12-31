
<?php
session_start();

include_once 'logic/GestorUsuario.php';
verificarSesionNoIniciada();
$_GLOBALS['sectionHeader'] = "Matriculación";

define('AUTHORIZED_SCRIPT', true);
//include_once 'db.php';

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
    $idProfesorPorDefecto = "0950191253";
    if (!is_null($idMateriaParalelo)) {
        // Ya existe un registro para esta materia y paralelo, mostrar mensaje
        $msg = "Ya estás inscrito en esta materia y paralelo.";
    } else {
        // No existe un registro, crear uno nuevo
        $idMateriaParalelo = crearMateriaParalelo($idMateria, $idParalelo, $idProfesorPorDefecto);

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
    /*$sqlInscribir = "INSERT INTO inscripciones (id_materia_paralelo, id_estudiante) VALUES (?, ?)";
    $stmtInscribir = mysqli_prepare($connection, $sqlInscribir);
    mysqli_stmt_bind_param($stmtInscribir, "is", $idMateriaParalelo, $idEstudiante);

    if (mysqli_stmt_execute($stmtInscribir)) {
        // Inscripción exitosa
        $msgInscripcion = "Inscripción exitosa. Ahora estás inscrito en la materia.";
    } else {
        // Error en la inscripción
        $msgInscripcion = "Error en la inscripción. Inténtalo de nuevo.";
    }

    mysqli_stmt_close($stmtInscribir);

    return $msgInscripcion;*/
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
    /*$sqlVerificar = "SELECT id FROM materia_paralelo WHERE id_materia = ? AND id_paralelo = ?";
    $stmtVerificar = mysqli_prepare($connection, $sqlVerificar);
    mysqli_stmt_bind_param($stmtVerificar, "ii", $idMateria, $idParalelo);
    mysqli_stmt_execute($stmtVerificar);
    $resultVerificar = mysqli_stmt_get_result($stmtVerificar);

    if (mysqli_num_rows($resultVerificar) > 0) {
        $row = mysqli_fetch_assoc($resultVerificar);
        $idMateriaParalelo = $row['id'];
    } else {
        $idMateriaParalelo = null;
    }

    mysqli_stmt_close($stmtVerificar);

    return $idMateriaParalelo;*/
    return $idMateriaParalelo;
}

function crearMateriaParalelo($idMateria, $idParalelo, $idProfesorPorDefecto) {
    include 'configs.php';
    include_once 'funcBack/inscripciones.php';
    $datos = array(
        'id_materia'=>$idMateria,
        'id_paralelo'=>$idParalelo,
        'id_docente'=>$idProfesorPorDefecto
    );
    $resultado = crearCurso($datos,$ip);
    /*$sqlInsertar = "INSERT INTO materia_paralelo (id_materia, id_paralelo, id_docente) VALUES (?, ?, ?)";
    $stmtInsertar = mysqli_prepare($connection, $sqlInsertar);
    mysqli_stmt_bind_param($stmtInsertar, "iis", $idMateria, $idParalelo, $idProfesorPorDefecto);

    if (mysqli_stmt_execute($stmtInsertar)) {
        $idMateriaParalelo = mysqli_insert_id($connection);
    } else {
        $idMateriaParalelo = null;
    }

    mysqli_stmt_close($stmtInsertar);

    return $idMateriaParalelo;*/
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
    include_once 'funcBackinscripciones.php';
    $datos = array(
        'id_curso'=>$idMateriaParalelo,
        'id_docente'=>$idDocente
    );
    $respuesta = actualizaDocente($datos,$ip);
    /*$sqlActualizar = "UPDATE materia_paralelo SET id_docente = ? WHERE id = ?";
    $stmtActualizar = mysqli_prepare($connection, $sqlActualizar);
    mysqli_stmt_bind_param($stmtActualizar, "si", $idDocente, $idMateriaParalelo);

    if (mysqli_stmt_execute($stmtActualizar)) {
        $msg = "Vinculación actualizada exitosamente.";
    } else {
        $msg = "Error al actualizar la vinculación. Inténtalo de nuevo.";
    }

    mysqli_stmt_close($stmtActualizar);

    return $msg;*/
    return $respuesta;
}

?>

