<?php
session_start();
if (isset($_SESSION['usuario']) == null) {
    header("Location: ../views/index.php");
}
include_once '../config/config.php';

// Obtener el ID del usuario a editar desde la URL
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : '';
// Consulta para obtener los datos actuales del usuario
$consulta = "SELECT * FROM usuarios WHERE idUsuario='$idUsuario'";
$ejecutar_consulta = mysqli_query($con, $consulta);
$datos = mysqli_fetch_array($ejecutar_consulta);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Editar Usuario</title>
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
    </br>
    <div class="container mt-5">
        <h1 class="mb-4 text-center fw-bold">Editar Usuario</h1>
        <form action="" method="POST" class="form-control shadow-sm p-4">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="nombreUsuario" class="form-label fw-bold">Usuario</label>
                    <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" value="<?php echo $datos['nombreUsuario']; ?>" placeholder="Ingrese su nombre de usuario" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="email" class="form-label fw-bold">Correo</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $datos['email']; ?>" placeholder="Ingrese su correo válido" required>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
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
    $estado_usuario = "SELECT nombreUsuario AND email FROM usuarios WHERE idUsuario='$idUsuario'";

    $verificar_sql = mysqli_query($con, $estado_usuario);
    $verificar_datos = mysqli_fetch_assoc($verificar_sql);
    $var_usuario = $verificar_datos['nombreUsuario'];
    $var_email = $verificar_datos['email'];

    // Si no hay cambios redirigir sin actualizar
    if ($nombreUsuario === $datos['nombreUsuario'] && $email === $datos['email']) {
        echo "<script>alert('No realizaste ningún cambio en los datos.'); window.location.href = '../views/usuarios.php';</script>";
    } else {
        // Verificar si el nombre de usuario y/o correo ya existe
        $verificar_nombre = "SELECT idUsuario FROM usuarios WHERE nombreUsuario='$nombreUsuario' AND idUsuario != '$idUsuario'";
        $resultado_nombre = mysqli_query($con, $verificar_nombre);
        if (mysqli_num_rows($resultado_nombre) > 0) {
            echo "<script>alert('El nombre de usuario ya está en uso. Por favor, elige otro.'); window.location.href = 'editUser.php?idUsuario=$idUsuario';</script>";
            exit();
        }
        $verificar_email = "SELECT idUsuario FROM usuarios WHERE email='$email' AND idUsuario != '$idUsuario'";
        $resultado_email = mysqli_query($con, $verificar_email);
        if (mysqli_num_rows($resultado_email) > 0) {
            echo "<script>alert('El correo electrónico ya está en uso. Por favor, elige otro.'); window.location.href = 'editUser.php?idUsuario=$idUsuario';</script>";
            exit();
        }

        // Actualizar los datos del usuario en la base de datos
        $actualizar = "UPDATE usuarios SET nombreUsuario='$nombreUsuario', email='$email' WHERE idUsuario='$idUsuario'";
        if (mysqli_query($con, $actualizar)) {
            echo "<script>alert('Datos actualizados exitosamente.'); window.location.href = '../views/usuarios.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar los datos" . mysqli_error($con) . "'); window.location.href = 'editUser.php?idUsuario=$idUsuario';</script>";
        }
    }
    // Cerrar la conexión a la base de datos
    mysqli_close($con);
}
?>