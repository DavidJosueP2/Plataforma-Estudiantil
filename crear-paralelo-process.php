<?php
define('AUTHORIZED_SCRIPT', true);
include 'configs.php';
// Función para obtener opciones del menú
function getNiveles()
{
    include 'configs.php';
    $urlGet = $ip . 'niveles/';
    $jsonResponse = file_get_contents($urlGet);
    $data = json_decode($jsonResponse, true);
    if (!is_null($data)) {
        $opcionesHTML = '<option value="" selected disabled>Seleccione Nivel</option>';
        foreach ($data as $nivel) {
            $opcionesHTML .= "<option value='{$nivel['nombre_nivel']}'>{$nivel['nombre_nivel']}</option>";
        }
        return $opcionesHTML;
    } else {
        return '<option value="" selected disabled>Error al obtener niveles</option>';
    }
}
// Verificar si se ha enviado el formulario
function creaParalelo($nombreParalelo, $nombreNivel)
{
    if (empty($nombreParalelo)) {
        $res = "Error: El nombre de la materia no puede estar vacío.";
        return $res;
    }
    include 'configs.php';
    include_once 'funcBack/paralelos.php';
    // Obtener el ID del nivel desde la tabla niveles
    $datos = array(
        'nombre_nivel' => $nombreNivel,
        'nombre_paralelo' => $nombreParalelo
    );
    $res = crearParalelo($datos, $ip);
    return $res;
}
?>
