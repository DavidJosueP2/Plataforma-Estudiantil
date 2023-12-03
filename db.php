<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Pepinillos2005$awgame');
define('DB_DATABASE', 'plataforma_estudiantil');

$connection = @mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$base_url='http://localhost/pruebaCarpeta/';

if (!$connection) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
