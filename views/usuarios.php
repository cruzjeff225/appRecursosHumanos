<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/index.php");
}

include_once '../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
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
    include_once('nav.php');
    $idSesion = $_SESSION['idUsuario']; // id del usuario logueado
    // Consulta SQL para obtener usuarios
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
        $search = mysqli_real_escape_string($con, $_POST['search']);
        $query = "SELECT * FROM usuarios WHERE (nombreUsuario LIKE '%$search%' OR email LIKE '%$search%') AND idUsuario != '$idSesion'";
    } else {
        // Si no hay búsqueda, obtener todos los usuarios
        $query = "SELECT * FROM usuarios WHERE idUsuario != '$idSesion'";

    }

    // Ejecutar la consulta
    $ejecutar_consulta = mysqli_query($con, $query);
    // Inicializar el contador
    $i = 1;
    ?>
    </br>
    <div class="container mt-5">
        <h1 class="mb-4 text-center fw-bold">Gestión de Usuarios</h1>
        <div class="d-flex justify-content-start mb-3">
            <a href="../user/addUser.php" class="btn btn-success d-flex align-items-center" style="white-space: nowrap; height: 38px;">
                <i class="fas fa-user-plus"></i>&nbsp;Nuevo Usuario
            </a>
        </div>
        <div class="d-flex justify-content-between mb-4">
            <!-- Search form -->
            <form class="d-flex justify-content-center align-items-center gap-2" action="" method="POST" style="width: 500px; margin: 0 auto;">
                <input class="form-control flex-grow-1" type="text" name="search" placeholder="Buscar por usuario o correo" style="height: 38px; " value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>">
                <button class="btn btn-primary d-flex align-items-center justify-content-center" type="submit" style="height: 38px;">
                    <i class="fas fa-search me-1"></i> Buscar
                </button>
            </form>
        </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <th>N°</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Acción</th>
                        <th>Contraseña</th>
                    </tr>
                    <?php
                    while ($lista = mysqli_fetch_array($ejecutar_consulta)) {
                    ?>
                        <tr>
                        <td><?php echo $i++ ?></td>
                            <td><?php echo $lista['nombreUsuario'] ?></td>
                            <td><?php echo $lista['email'] ?></td>
                            <td class="align-middle">
                                <div class="d-flex  align-items-center gap-2">
                                    <a href="../user/editUser.php?idUsuario=<?php echo $lista['idUsuario'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="../user/deleteUser.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');" class="m-0">
                                        <input type="hidden" name="idUsuario" value="<?php echo $lista['idUsuario'] ?>">
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="align-middle">
                                <a href="../user/editPassword.php?idUsuario=<?php echo $lista['idUsuario'] ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-key"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>

                </table>
            </div>
        </div>
        <?php
        // Cerrar la conexión a la base de datos
        mysqli_close($con);
        ?>
</body>

</html>