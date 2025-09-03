<?php
session_start();
if (isset($_SESSION['usuario']) == null) {
    header("Location: ../views/index.php");
}
include_once '../config/config.php';
// vAriables para capturar los datos del formulario
$nombreUsuario = isset($_POST['nombreUsuario']) ? $_POST['nombreUsuario'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Agregar Usuario</title>
    <style>
        .contenido {
            margin: 40px;
        }
    </style>
</head>

<body>
    <?php
    include_once('../views/nav.php');
    ?>
    <div class="contenido">
        <form action="" method="POST" class="form-control">
            <label for="nombreUsuario" class="form-label">Usuario</label>
            <input type="text" name="nombreUsuario" id="nombreUsuario" class="form-control" required placeholder="Ingrese su nombre de usuario" value="<?php echo $nombreUsuario ?>">

            <label for="email" class="form-label">Correo</label>
            <input type="email" name="email" id="email" class="form-control" required placeholder="Ingrese su correo valido" value="<?php echo $email ?>">

            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required placeholder="*********">
            <br>
            <input type="submit" class="form-control btn btn-primary" value="Registrar">
        </form>
    </div>
</body>

</html>

<?php
// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario
    $nombreUsuario = isset($_POST['nombreUsuario']) ? $_POST['nombreUsuario'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    // Hashear la contraseña
    $passwordHash = md5($password);

    // Verificar si el nombre de usuario ya existe
    $verificar_usuario = "SELECT * FROM usuarios WHERE nombreUsuario='$nombreUsuario' OR email='$email'";
    $resultado = mysqli_query($con, $verificar_usuario);
    if (mysqli_num_rows($resultado) >=1 ) {
        echo "<script>alert('El nombre de usuario ya existe. Prueba con otro.'); window.location.href = 'addUser.php';</script>";
    } else {
        // Insertar nuevo usuario en la base de datos
        $insertar = "INSERT INTO usuarios (nombreUsuario, email, password) VALUES ('$nombreUsuario', '$email', '$passwordHash')";
        if (mysqli_query($con, $insertar)) {
            echo "<script>alert('Usuario registrado exitosamente.'); window.location.href = '../views/usuarios.php';</script>";
        } else {
            echo "<script>alert('Error al registrar el usuario: " . mysqli_error($con) . "'); window.location.href = 'addUser.php';</script>";
        }
    }
    // Cerrar la conexión a la base de datos
    mysqli_close($con);
}
?>
