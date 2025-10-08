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
    <title>Gestión de Personal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .contenido {
            margin: 40px;
        }
    </style>
</head>

<body>
    <?php include_once('nav.php'); ?>

    <?php
    // Consulta para obtener todo el personal
    $consulta = "SELECT * FROM personal";
    $ejecutar_consulta = mysqli_query($con, $consulta);
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
                    <th>Fotografía</th>
                    <th>Acción</th>
                </tr>

                <?php while ($lista = mysqli_fetch_array($ejecutar_consulta)) { ?>
                    <tr class="align-middle">
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $lista['nombre']; ?></td>
                        <td><?php echo $lista['Telefono']; ?></td>
                        <td><?php echo $lista['DUI']; ?></td>
                        <td><?php echo $lista['fechaNacimiento']; ?></td>
                        <td><?php echo $lista['departamento']; ?></td>
                        <td>
                            <?php
                            $fotografía = (!empty($lista['fotografía']) && $lista['fotografía'] != "user.png")
                                ? "../img/imgEmployees/" . $lista['fotografía']
                                : "../img/user.png";
                            ?>
                            <img src="<?php echo $fotografía; ?>" alt="Fotografía de <?php echo $lista['nombre']; ?>" style="width: 40px; height: 40px; border-radius: 50%; ">
                        </td>
                        <td>
                            <div class="d-flex  align-items-center gap-2">
                                <a href="../employees/editEmployee.php?idPersonal=<?php echo $lista['idPersonal']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="../employees/deleteEmployee.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');" class="m-0 p-0">
                                    <input type="hidden" name="idPersonal" value="<?php echo $lista['idPersonal']; ?>">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#verModal<?php echo $lista['idPersonal']; ?>">
                                    <i class="fas fa-eye"></i> Ver más
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal de Detalles del Empleado -->
                    <div class="modal fade" id="verModal<?php echo $lista['idPersonal']; ?>" tabindex="-1" aria-labelledby="verModalLabel<?php echo $lista['idPersonal']; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 500px;">
                            <div class="modal-content border-0 shadow-sm rounded-3" style="background: #f8f9fa; min-width: 400px;">
                                <div class="modal-header p-2" style="background: #343a40; color: #fff; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                                    <h6 class="modal-title fw-semibold" id="verModalLabel<?php echo $lista['idPersonal']; ?>">Detalles de: <?php echo $lista['nombre']; ?></h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="d-flex align-items-center gap-4">
                                        <img src="<?php echo $fotografía; ?>" alt="avatar" class="rounded-circle border" width="80" height="80">
                                        <div class="flex-grow-1">
                                            <div class="mb-2"><span class="fw-bold">Nombre:</span> <?php echo $lista['nombre']; ?></div>
                                            <div class="mb-2"><span class="fw-bold">Teléfono:</span> <?php echo preg_replace('/^(\d{4})(\d{4})$/', '$1-$2', $lista['Telefono']); ?></div>
                                            <div class="mb-2"><span class="fw-bold">DUI:</span> <?php echo preg_replace('/^(\d{8})(\d)$/', '$1-$2', $lista['DUI']); ?></div>
                                            <div class="mb-2"><span class="fw-bold">Nacimiento:</span> <?php echo $lista['fechaNacimiento']; ?></div>
                                            <div class="mb-2"><span class="fw-bold">Departamento:</span> <?php echo $lista['departamento']; ?></div>
                                            <div class="mb-2"><span class="fw-bold">Distrito:</span> <?php echo $lista['distrito']; ?></div>
                                            <div class="mb-2"><span class="fw-bold">Colonia:</span> <?php echo $lista['coloniaResidencia']; ?></div>
                                            <div class="mb-2"><span class="fw-bold">Calle:</span> <?php echo $lista['calleResidencia']; ?></div>
                                            <div class="mb-2"><span class="fw-bold">Casa:</span> <?php echo $lista['casaResidencia']; ?></div>
                                            <div class="mb-2"><span class="fw-bold">Estado Civil:</span> <?php echo $lista['estadoCivil']; ?></div>
                                            <div class="mb-2"><span class="fw-bold">Registro:</span> <?php echo date('Y-m-d', strtotime($lista['fechaRegistro'])); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer p-2 border-0">
                                    <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fin del Modal -->
                <?php } ?>
            </table>
        </div>
    </div>

    <?php mysqli_close($con); ?>

    <!-- Bootstrap Bundle JS (necesario para modales) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>