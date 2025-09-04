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
    // Consulta para obtener todo el personal
    $consulta = "SELECT * FROM personal";
    // Ejecutar la consulta
    $ejecutar_consulta = mysqli_query($con, $consulta);
    // Inicializar el contador
    $i = 1;


    ?>
    </br>
    <div class="container mt-5">
    <h1 class="mb-4 text-center fw-bold">Gestión de Personal</h1>
    <div class="d-flex justify-content-between mb-4">
        <a href="../employees/addEmployee.php" class="btn btn-success">
            <i class="fas fa-user-plus"></i> Nuevo Empleado
        </a>
    </div>

        <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <th>N°</th>
                <th>Empleado</th>
                <th>Teléfono</th>
                <th>DUI</th>
                <th>Fecha de Nacimiento</th>
                <th>Departamento</th>
                <th>Distrito</th>
                <th>Colonia</th>
                <th>Calle</th>
                <th>Casa</th>
                <th>Estado Civil</th>
                <th>Fotografía</th>
                <th>Fecha de Registro</th>
                <th>Acción</th>
                <th></th>
                <th></th>
            </tr>
            <?php
            while ($lista = mysqli_fetch_array($ejecutar_consulta)) {
            ?>
                <tr>
                    <td><?php echo $i++ ?></td>
                    <td><?php echo $lista['nombre'] ?></td>
                    <td><?php echo $lista['Telefono'] ?></td>
                    <td><?php echo $lista['DUI'] ?></td>
                    <td><?php echo $lista['fechaNacimiento'] ?></td>
                    <td><?php echo $lista['departamento'] ?></td>
                    <td><?php echo $lista['distrito'] ?></td>
                    <td><?php echo $lista['coloniaResidencia'] ?></td>
                    <td><?php echo $lista['calleResidencia'] ?></td>
                    <td><?php echo $lista['casaResidencia'] ?></td>
                    <td><?php echo $lista['estadoCivil'] ?></td>
                    <td><?php echo $lista['fotografía'] ?></td>
                    <!-- Formatear la fecha de registro -->
                    <td><?php echo date ('Y-m-d', strtotime($lista['fechaRegistro'])) ?></td>
                    <td>
                            <div class="d-flex gap-2">
                                <a href="../employees/editEmployee.php?idPersonal=<?php echo $lista['idPersonal'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="../employees/deleteEmployee.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                    <input type="hidden" name="idPersonal" value="<?php echo $lista['idPersonal'] ?>">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
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