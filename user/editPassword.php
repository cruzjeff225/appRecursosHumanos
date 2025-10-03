<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/index.php");
}

// Verificar que sea superadmin
if (!isset($_SESSION['RolNombre']) || $_SESSION['RolNombre'] !== 'superadmin') {
    echo "<script>alert('No tienes permisos para cambiar contraseñas.'); window.location.href = '../views/usuarios.php';</script>";
    exit;
}

include_once '../config/config.php';

$usuarioId = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $passwordHash = md5($password);

    $update = "UPDATE usuarios SET password = '$passwordHash' WHERE idUsuario = $usuarioId";

    if (mysqli_query($con, $update)) {
        echo "<script>alert('Contraseña cambiada exitosamente.'); window.location.href = '../views/usuarios.php';</script>";
    } else {
        echo "<script>alert('Error al cambiar la contraseña: " . mysqli_error($con) . "');</script>";
    }

    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        <h1 class="mb-4 text-center fw-bold">Registro de Usuario</h1>
        <form action="" method="POST" class="form-control shadow rounded p-5">
            <div class="row mb-4">
                <input type="hidden" name="idUsuario" value="<?php echo $usuarioId; ?>">
                <div class="col-md-12">
                    <label for="password" class="form-label fw-light">Contraseña</label>
                    <input type="password" class="form-control rounded-pill" id="password" name="password" placeholder="*********" required>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                    <i class="fas fa-save"></i> Cambiar contraseña
                </button>
            </div>
        </form>
    </div>
</body>
</html>