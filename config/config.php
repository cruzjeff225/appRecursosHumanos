<?php 
$server = 'localhost';
$username = 'root';
$password = 'root';
$database = 'dbRRHH';

$con = new mysqli($server, $username, $password, $database);

// Check connection
if ($con) {
    // echo "Conexion exitosa";
} else {
    echo "Ha ocurrido un error en la conexion";
}