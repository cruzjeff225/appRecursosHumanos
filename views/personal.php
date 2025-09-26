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
                    <tr>
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
                            <img src="<?php echo $fotografía; ?>" alt="Fotografía de <?php echo $lista['nombre']; ?>" style="width: 50px; height: 50px; border-radius: 50%;">
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="../employees/editEmployee.php?idPersonal=<?php echo $lista['idPersonal']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="../employees/deleteEmployee.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
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
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="verModalLabel<?php echo $lista['idPersonal']; ?>">Detalles de <?php echo $lista['nombre']; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="<?php echo $fotografía; ?>" alt="avatar" class="img-thumbnail" width="150" height="150">
                                </div>
                                <div class="col-md-8">
                                    <p><strong>Nombre:</strong> <?php echo $lista['nombre']; ?></p>
                                    <p><strong>Teléfono:</strong> <?php echo $lista['Telefono']; ?></p>
                                    <p><strong>DUI:</strong> <?php echo $lista['DUI']; ?></p>
                                    <p><strong>Fecha de nacimiento:</strong> <?php echo $lista['fechaNacimiento']; ?></p>
                                    <p><strong>Departamento:</strong> <?php echo $lista['departamento']; ?></p>
                                    <p><strong>Distrito:</strong> <?php echo $lista['distrito']; ?></p>
                                    <p><strong>Colonia:</strong> <?php echo $lista['coloniaResidencia']; ?></p>
                                    <p><strong>Calle:</strong> <?php echo $lista['calleResidencia']; ?></p>
                                    <p><strong>Casa:</strong> <?php echo $lista['casaResidencia']; ?></p>
                                    <p><strong>Estado Civil:</strong> <?php echo $lista['estadoCivil']; ?></p>
                                    <p><strong>Fecha de registro:</strong> <?php echo date('Y-m-d', strtotime($lista['fechaRegistro'])); ?></p>
                                </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
