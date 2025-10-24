<?php 
$server = 'localhost';
$username = 'root';
$password = 'root';
$database = 'dbRRHH';

$con = new mysqli($server, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
} else {

}