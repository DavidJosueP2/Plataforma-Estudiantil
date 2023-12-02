<?php
$servidor = "localhost";
$usuario = "root";
$clave = "Pepinillos2005\$awgame";
$db = "ariel";

try {
    $conexion = new PDO("mysql:host=" . $servidor . ";dbname=" . $db, $usuario, $clave);
    echo "Se conectó a la base de datos";
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
