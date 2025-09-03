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
    <div class="contenido">
        <br>
        <a href="../user/addUser.php" class="btn btn-success">Nuevo Usuario</a>
        <br>

        <table class="table">
            <tr>
                <th>N°</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Acción</th>
                <th></th>
                <th></th>
                <th>Admin Contraseña</th>
            </tr>
            <?php
            while ($lista = mysqli_fetch_array($ejecutar_consulta)) {
            ?>
                <tr>
                    <td><?php echo $i++ ?></td>
                    <td><?php echo $lista['nombreUsuario'] ?></td>
                    <td><?php echo $lista['email'] ?></td>
                    <td><a href="../user/editUser.php?idUsuario=<?php echo $lista['idUsuario'] ?>" class="btn btn-primary" value="Editar">Editar</a>
                    </td>
                    <td>
                        <form action="eliminar-usuario.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $lista['idUsuario'] ?>">
                            <button class="btn btn-danger">Eliminar</button>
                        </form>
                    <td>
                    <td>
                        <button class="btn btn-warning" value="Eliminar" title="Modificar contraseña">Contraseña</button>
                    </td>
                </tr>
            <?php
            }
            ?>

        </table>
    </div>
    <?php
    // Cerrar la conexión a la base de datos
    mysqli_close($con);
    ?>
</body>

</html>