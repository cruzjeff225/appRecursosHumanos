<?php
include_once '../config/config.php';

// Recibir datos del formulario
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
// Encriptar la contraseña usando MD5
$pwdFormat = md5($password);

// Consulta SQL para verificar las credenciales
$query = "SELECT idUsuario, nombreUsuario, email, password, r.Rol AS RolNombre FROM usuarios JOIN Rol r ON RolId = r.IdRol WHERE email = '$email' AND password = '$pwdFormat'";
$result = (mysqli_query($con, $query));

$usuario = mysqli_fetch_assoc($result);
// Iniciar sesión y almacenar el nombre de usuario en una variable de sesión
session_start();
$_SESSION['idUsuario'] = $usuario['idUsuario'];
$_SESSION['usuario'] = $usuario ['nombreUsuario'];
$_SESSION['RolNombre'] = $usuario['RolNombre'];

// Verificar si se encontró un usuario con las credenciales proporcionadas
$validation = mysqli_num_rows($result);
if ($validation > 0) {
    header("Location: ../views/home.php");
} else {
    header('Location: ../views/index.php?error=error');
}
?>
