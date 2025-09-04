<?php
session_start();
if (isset($_SESSION['usuario']) == null) {
    header("Location: ../views/index.php");
}
include_once '../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    // Consulta para obtener los usuarios
    $consulta = "SELECT * FROM usuarios";
    // Ejecutar la consulta
    $ejecutar_consulta = mysqli_query($con, $consulta);
    // Inicializar el contador
    $i = 1;


    ?>
    </br>
    <div class="container mt-5">
    <h1 class="mb-4 text-center fw-bold">Gestión de Usuarios</h1>
    <div class="d-flex justify-content-between mb-4">
        <a href="../user/addUser.php" class="btn btn-success">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>

        <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <th>N°</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Acción</th>
                <th>Admin Contraseña</th>
            </tr>
            <?php
            while ($lista = mysqli_fetch_array($ejecutar_consulta)) {
            ?>
                <tr>
                    <td><?php echo $i++ ?></td>
                    <td><?php echo $lista['nombreUsuario'] ?></td>
                    <td><?php echo $lista['email'] ?></td>
                    <td>
                            <div class="d-flex gap-2">
                                <a href="../user/editUser.php?idUsuario=<?php echo $lista['idUsuario'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="../user/deleteUser.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                    <input type="hidden" name="idUsuario" value="<?php echo $lista['idUsuario'] ?>">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    <td>
                        <button class="btn btn-warning btn-sm" value="Eliminar" title="Modificar contraseña">
                            <i class="fas fa-key"></i>
                        </button>
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