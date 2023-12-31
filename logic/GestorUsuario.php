<?php
    
    include 'funcBack/selectAll.php';
    // Verificar si el usuario NO ha iniciado sesión
    function verificarSesionNoIniciada() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            // Si el usuario NO ha iniciado sesión, redirigir a la página "index"
            header("Location: index.php");
            exit();
        }
    }

    // Verificar si el usuario ha iniciado sesión
    function verificarSesionIniciada() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            // Si el usuario ha iniciado sesión, redirigir a la página de "home"
            header("Location: home.php");
            exit();
        }
    }

    // Obtener detalles del usuario basados en el user_id
    function obtenerDetallesUsuario($user_id) {
        include_once 'configs.php';
        $datos = array(
            'id'=>$user_id
        );
        $filas = selectRestrictedID($datos,$ip);
        return $filas;
    }

    // Actualizar detalles del usuario
    function actualizarDetallesUsuario($user_id, $nuevos_datos) {
        global $connection;

        // Lógica para actualizar detalles del usuario en la base de datos
        // ...
    }

    // Cerrar sesión y redirigir al usuario a la página de inicio de sesión
    function cerrarSesion() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        session_destroy();
        header("Location: index.php");
        exit();
    }    

    // Cambiar la contraseña del usuario
    function cambiarContrasena($user_id, $nueva_contrasena) {
        global $connection;

        // Lógica para cambiar la contraseña del usuario en la base de datos
        // ...
    }

    // Validar datos del formulario
    function validarFormulario($datos_formulario) {
        // Lógica para validar los datos del formulario antes de procesarlos
        // ...
    }
?>
