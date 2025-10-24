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
    </br>
    <title>Gestión de Personal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alertify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <style>
        .contenido {
            margin: 40px;
        }
        /* Alertify */
        .alertify-notifier .ajs-message {
            color: #fff;
            background: #FFDE3D;
            border-radius: 5px;
            padding: .5rem 1rem;
            font-weight: 500;
            transition: transform .12s ease, opacity .12s ease;
        }
        .alertify-notifier .ajs-message.ajs-success {
            background: #6AC763;
        }
    </style>
</head>

<body>
    <?php include_once('nav.php'); ?>

    <?php
    // Lógica de Paginación
    $registrosPorPágina = 10;
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($paginaActual - 1) * $registrosPorPágina;

    // Contar total de personal
    $totalPersonalQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM personal");
    $totalPersonalRow = mysqli_fetch_assoc($totalPersonalQuery)['total'];
    $totalPaginas = ceil($totalPersonalRow / $registrosPorPágina);
    // Consulta para obtener todo el personal con límite y offset
    $consulta = "SELECT * FROM personal LIMIT $registrosPorPágina OFFSET $offset";
    $ejecutar_consulta = mysqli_query($con, $consulta);
    $i = 1;
    ?>

    </br>
    <div class="container mt-5">
        <h1 class="mb-4 text-center fw-bold">Gestión de Personal</h1>
        <div class="d-flex justify-content-between mb-4">
            <button id="btnNewEmployee" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#employeeModal">
                <i class="fas fa-user-plus"></i> Nuevo Empleado
            </button>
        </div>

        <!-- Barra de búsqueda -->
        <div class="d-flex justify-content-between mb-4">
            <input id="search" class="form-control" type="text" placeholder="Buscar por nombre o DUI..." style="max-width: 500px; margin: 0 auto;">
        </div>

        <div id="tablaPersonal" class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <th>N°</th>
                    <th>Empleado</th>
                    <th>DUI</th>
                    <th>Departamento</th>
                    <th>Distrito</th>
                    <th>Fotografía</th>
                    <th>Acción</th>
                </tr>

                <?php while ($lista = mysqli_fetch_array($ejecutar_consulta)) { ?>
                    <tr class="align-middle" data-id="<?php echo $lista['idPersonal']; ?>">
                        <td class="emp-index"><?php echo $i++; ?></td>
                        <td class="emp-nombre"><?php echo htmlspecialchars($lista['nombre']); ?></td>
                        <td class="emp-dui"><?php echo $lista['DUI']; ?></td>
                        <td class="emp-departamento"><?php echo $lista['departamento']; ?></td>
                        <td class="emp-distrito"><?php echo $lista['distrito']; ?></td>
                        <td class="emp-foto">
                            <?php
                            $fotografía = (!empty($lista['fotografía']) && $lista['fotografía'] != "user.png")
                                ? "../img/imgEmployees/" . $lista['fotografía']
                                : "../img/user.png";
                            ?>
                            <img src="<?php echo $fotografía; ?>" alt="Fotografía" style="width:40px;height:40px;border-radius:50%;">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-primary btn-sm btn-edit" data-id="<?php echo $lista['idPersonal']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="../employees/deleteEmployee.php" method="POST" class="m-0 p-0 delete-form" data-id="<?php echo $lista['idPersonal']; ?>">
                                    <input type="hidden" name="idPersonal" value="<?php echo $lista['idPersonal']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
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
                <?php } ?>
            </table>
            <!-- Paginación -->
            <nav aria-label="Paginación de empleados">
                <ul class="pagination justify-content-center">
                    <!-- Botón Anterior -->
                    <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
                    </li>

                    <!-- Números de página -->
                    <?php for ($j = 1; $j <= $totalPaginas; $j++): ?>
                        <li class="page-item <?php echo ($paginaActual == $j) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $j; ?>"><?php echo $j; ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Botón Siguiente -->
                    <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal: Nuevo Empleado -->
    <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">Registrar Nuevo Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="employeeForm" class="row g-3" enctype="multipart/form-data">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                placeholder="Ingresa el nombre del empleado"
                                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{2,50}$"
                                title="Solo letras y espacios (2-50 caracteres)" required>
                        </div>
                        <!-- Teléfono -->
                        <div class="col-md-6">
                            <label for="Telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="Telefono" name="Telefono"
                                placeholder="Ingresa el teléfono"
                                pattern="^[267][0-9]{7}$"
                                title="Debe tener 8 dígitos, iniciando con 2, 6 o 7" required>
                        </div>
                        <!-- DUI -->
                        <div class="col-md-6">
                            <label for="DUI" class="form-label">DUI</label>
                            <input type="text" class="form-control" id="DUI" name="DUI"
                                placeholder="Ingresa el DUI"
                                pattern="^[0-9]{8}-?[0-9]$"
                                title="Debe tener 9 dígitos: 123456789. Sin guiones" required>
                        </div>
                        <!-- Fecha de Nacimiento -->
                        <div class="col-md-6">
                            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required>
                        </div>
                        <!-- Departamento -->
                        <div class="col-md-6">
                            <label for="departamento" class="form-label">Departamento</label>
                            <select id="departamento" class="form-select" name="departamento" required>
                                <option selected disabled>Seleccionar...</option>
                                <option>Ahuachapán</option>
                                <option>Cabañas</option>
                                <option>Chalatenango</option>
                                <option>Cuscatlán</option>
                                <option>La Libertad</option>
                                <option>La Paz</option>
                                <option>La Unión</option>
                                <option>Morazán</option>
                                <option>San Miguel</option>
                                <option>San Vicente</option>
                                <option>San Salvador</option>
                                <option>Santa Ana</option>
                                <option>Sonsonate</option>
                                <option>Usulután</option>
                            </select>
                        </div>
                        <!-- Distrito -->
                        <div class="col-md-6">
                            <label for="distrito" class="form-label">Distrito</label>
                            <input type="text" class="form-control" id="distrito" name="distrito"
                                placeholder="Ingresa el distrito"
                                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{2,50}$"
                                title="Solo letras y espacios" required>
                        </div>
                        <!-- Colonia Residencia -->
                        <div class="col-md-4">
                            <label for="coloniaResidencia" class="form-label">Colonia</label>
                            <input type="text" class="form-control" id="coloniaResidencia" name="coloniaResidencia"
                                placeholder="Ingresa la colonia"
                                pattern="^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ .#\-]{2,100}$"
                                title="Letras, números y símbolos . # -" required>
                        </div>
                        <!-- Calle Residencia -->
                        <div class="col-md-4">
                            <label for="calleResidencia" class="form-label">Calle</label>
                            <input type="text" class="form-control" id="calleResidencia" name="calleResidencia"
                                placeholder="Ingresa la calle"
                                pattern="^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ .#\-]{2,100}$"
                                title="Letras, números y símbolos . # -" required>
                        </div>
                        <!-- Casa Residencia -->
                        <div class="col-md-4">
                            <label for="casaResidencia" class="form-label">Casa</label>
                            <input type="text" class="form-control" id="casaResidencia" name="casaResidencia"
                                placeholder="Número o referencia de casa"
                                pattern="^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ .#\-]{1,50}$"
                                title="Letras, números o guiones" required>
                        </div>
                        <!-- Estado Civil -->
                        <div class="col-md-6">
                            <label for="estadoCivil" class="form-label">Estado Civil</label>
                            <select id="estadoCivil" class="form-select" name="estadoCivil" required>
                                <option selected disabled>Seleccionar...</option>
                                <option>Soltero</option>
                                <option>Casado</option>
                                <option>Divorciado</option>
                                <option>Viudo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="fotografia" class="form-label">Fotografía</label>
                            <input class="form-control" id="fotografia" type="file" name="fotografia" accept="image/*">
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" id="btnSubmitEmployee" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de edición que se maneja con updateEmployee.php -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm" enctype="multipart/form-data" class="row g-3">
                        <input type="hidden" name="idPersonal" id="edit_idPersonal">

                        <div class="col-md-6">
                            <label for="edit_nombre" class="form-label fw-bold">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre"
                                title="Solo letras y espacios (2-50 caracteres)" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_Telefono" class="form-label fw-bold">Teléfono</label>
                            <input type="text" class="form-control" id="edit_Telefono" name="Telefono"
                                pattern="^[267][0-9]{7}$"
                                title="Debe tener 8 dígitos, iniciando con 2, 6 o 7" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_DUI" class="form-label fw-bold">DUI</label>
                            <input type="text" class="form-control" id="edit_DUI" name="DUI"
                                pattern="^[0-9]{8}-?[0-9]$"
                                title="Debe tener 9 dígitos: 123456789. Sin guiones" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_fechaNacimiento" class="form-label fw-bold">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="edit_fechaNacimiento" name="fechaNacimiento" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Departamento</label>
                            <select id="edit_departamento" class="form-select" name="departamento" required>
                                <option selected disabled>Seleccionar...</option>
                                <option>Ahuachapán</option>
                                <option>Cabañas</option>
                                <option>Chalatenango</option>
                                <option>Cuscatlán</option>
                                <option>La Libertad</option>
                                <option>La Paz</option>
                                <option>La Unión</option>
                                <option>Morazán</option>
                                <option>San Miguel</option>
                                <option>San Vicente</option>
                                <option>San Salvador</option>
                                <option>Santa Ana</option>
                                <option>Sonsonate</option>
                                <option>Usulután</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_distrito" class="form-label fw-bold">Distrito</label>
                            <input type="text" class="form-control" id="edit_distrito" name="distrito"
                                title="Solo letras y espacios" required>
                        </div>

                        <div class="col-md-4">
                            <label for="edit_coloniaResidencia" class="form-label fw-bold">Colonia</label>
                            <input type="text" class="form-control" id="edit_coloniaResidencia" name="coloniaResidencia"
                                title="Letras, números y símbolos . # -" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_calleResidencia" class="form-label fw-bold">Calle</label>
                            <input type="text" class="form-control" id="edit_calleResidencia" name="calleResidencia"
                                title="Letras, números y símbolos . # -" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_casaResidencia" class="form-label fw-bold">Casa</label>
                            <input type="text" class="form-control" id="edit_casaResidencia" name="casaResidencia"
                                title="Letras, números o guiones" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_estadoCivil" class="form-label fw-bold">Estado Civil</label>
                            <select id="edit_estadoCivil" class="form-select" name="estadoCivil" required>
                                <option selected disabled>Seleccionar...</option>
                                <option>Soltero</option>
                                <option>Casado</option>
                                <option>Divorciado</option>
                                <option>Viudo</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_fotografia" class="form-label fw-bold">Fotografía</label>
                            <input class="form-control" id="edit_fotografia" type="file" name="fotografia" accept="image/*">
                            <small id="edit_currentPhoto" class="text-muted"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnUpdateEmployee" class="btn btn-primary">Guardar cambios</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <?php mysqli_close($con); ?>

    <!-- Bootstrap Bundle JS (necesario para modales) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (necesario para AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Alertify JS -->
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var query = $(this).val();

                $.ajax({
                    url: '../employees/buscar_personal.php',
                    method: 'POST',
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('#tablaPersonal').html(data);
                    }
                });
            });
        });
    </script>
    <!-- Personalización de Alerta con Alertify JS -->
    <script>
        if (window.alertify) {
            alertify.defaults.glossary = alertify.defaults.glossary || {};
            alertify.defaults.glossary.ok = 'Eliminar';
            alertify.defaults.glossary.cancel = 'Cancelar';
            alertify.defaults.glossary.title = 'Confirmación';

            // Temas de botones
            alertify.defaults.theme = alertify.defaults.theme || {};
            alertify.defaults.theme.ok = 'btn btn-primary';
            alertify.defaults.theme.cancel = 'btn btn-danger';

            // Posición del Notifier
            alertify.defaults.notifier = alertify.defaults.notifier || {};
            alertify.defaults.notifier.position = 'back-right';
        }
    </script>

    <!-- Elimincación con Alertify JS AJAX -->
    <script>
        // Manejar el envío del formulario de eliminación
        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            var form = this;
            var id = $(form).data('id') || $(form).find('input[name="idPersonal"]').val();

            alertify.confirm('Confirmar eliminación', '¿Estás seguro de que deseas eliminar este usuario?',
                function() {
                    // Enviar petición AJAX
                    var fd = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: fd,
                        credentials: 'same-origin'
                    }).then(function(res) {
                        return res.text().then(function(text) {
                            // Parsear JSON
                            try {
                                var json = JSON.parse(text);
                                if (json.success) {
                                    // Remover fila
                                    var tr = document.querySelector('tr[data-id="' + id + '"]');
                                    if (tr) tr.remove();
                                    alertify.success(json.message || 'Eliminado');
                                } else {
                                    alertify.error(json.message || 'Error al eliminar');
                                }
                            } catch (err) {
                                // No JSON
                                if (res.ok) {
                                    var tr = document.querySelector('tr[data-id="' + id + '"]');
                                    if (tr) tr.remove();
                                    alertify.success('Eliminado');
                                } else {
                                    alertify.error('Error al eliminar');
                                }
                            }
                        });
                    }).catch(function() {
                        alertify.error('Error de red');
                    });
                },
                function() {
                    alertify.message('Acción cancelada');
                }
            );
        });
    </script>

    <script>
        (function() {
            const form = document.getElementById('employeeForm');
            const submitBtn = document.getElementById('btnSubmitEmployee');
            const tbody = document.querySelector('table.table tbody') || document.querySelector('table.table');

            function formatPhone(phone) {
                return phone.replace(/^(\d{4})(\d{4})$/, '$1-$2');
            }

            function formatDUI(dui) {
                return dui.replace(/^(\d{8})(\d)$/, '$1-$2');
            }

            submitBtn.addEventListener('click', async function() {
                // Validar el formulario antes de enviarlo usando HTML5 validation
                if (!form.checkValidity()) {
                    // Mostrar mensajes de validación
                    form.reportValidity();
                    return;
                }

                submitBtn.disabled = true;
                const fd = new FormData(form);

                try {
                    const res = await fetch('../employees/addEmployee.php', {
                        method: 'POST',
                        body: fd,
                        credentials: 'same-origin'
                    });

                    // parsear JSON con manejo de errores
                    let data;
                    try {
                        data = await res.json();
                    } catch (parseErr) {
                        console.error('Respuesta no es JSON válido', parseErr);
                        alert('Respuesta inválida del servidor.');
                        return;
                    }

                    if (!res.ok) {
                        alert(data.message || 'Error al procesar la solicitud');
                        return;
                    }

                    // / Insertar nueva fila en la tabla
                    const emp = data.data;
                    // Enocontrar la tabla y su cuerpo
                    const table = document.querySelector('table.table');
                    const tbody = table.querySelector('tbody') || table;

                    // Nuevo indice
                    const lastIndexCell = table.querySelectorAll('tbody tr td:first-child');
                    const newIndex = (lastIndexCell.length ? lastIndexCell.length + 1 : table.querySelectorAll('tr').length);

                    const photoSrc = emp.fotografia && emp.fotografia !== 'user.png' ? '../img/imgEmployees/' + emp.fotografia : '../img/user.png';

                    // Crear nueva fila
                    const tr = document.createElement('tr');
                    tr.className = 'align-middle';
                    tr.innerHTML = `
                        <td>${newIndex}</td>
                        <td>${emp.nombre}</td>
                        <td>${emp.Telefono}</td>
                        <td>${emp.DUI}</td>
                        <td>${emp.fechaNacimiento}</td>
                        <td>${emp.departamento}</td>
                        <td><img src="${photoSrc}" alt="Fotografía de ${emp.nombre}" style="width:40px;height:40px;border-radius:50%;"></td>
                        <td>
                            <div class="d-flex  align-items-center gap-2">
                                <a href="../employees/editEmployee.php?idPersonal=${emp.idPersonal}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="../employees/deleteEmployee.php" method="POST" class="m-0 p-0 delete-form" data-id="${emp.idPersonal}">
                                    <input type="hidden" name="idPersonal" value="${emp.idPersonal}">
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#verModal${emp.idPersonal}">
                                    <i class="fas fa-eye"></i> Ver más
                                </button>
                            </div>
                        </td>
                    `;

                    // Añadir la fila al cuerpo de la tabla si existe. Si no añadir directamente a la tabla
                    let appendTarget = table.querySelector('tbody');
                    if (appendTarget) appendTarget.appendChild(tr);
                    else table.appendChild(tr);

                    // Modal de detalles
                    const modalHtml = `
                    <div class="modal fade" id="verModal${emp.idPersonal}" tabindex="-1" aria-labelledby="verModalLabel${emp.idPersonal}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 500px;">
                            <div class="modal-content border-0 shadow-sm rounded-3" style="background: #f8f9fa; min-width: 400px;">
                                <div class="modal-header p-2" style="background: #343a40; color: #fff; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                                    <h6 class="modal-title fw-semibold" id="verModalLabel${emp.idPersonal}">Detalles de: ${emp.nombre}</h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="d-flex align-items-center gap-4">
                                        <img src="${photoSrc}" alt="avatar" class="rounded-circle border" width="80" height="80">
                                        <div class="flex-grow-1">
                                            <div class="mb-2"><span class="fw-bold">Nombre:</span> ${emp.nombre}</div>
                                            <div class="mb-2"><span class="fw-bold">Teléfono:</span> ${emp.Telefono}</div>
                                            <div class="mb-2"><span class="fw-bold">DUI:</span> ${emp.DUI}</div>
                                            <div class="mb-2"><span class="fw-bold">Nacimiento:</span> ${emp.fechaNacimiento}</div>
                                            <div class="mb-2"><span class="fw-bold">Departamento:</span> ${emp.departamento}</div>
                                            <div class="mb-2"><span class="fw-bold">Distrito:</span> ${emp.distrito}</div>
                                            <div class="mb-2"><span class="fw-bold">Colonia:</span> ${emp.coloniaResidencia}</div>
                                            <div class="mb-2"><span class="fw-bold">Calle:</span> ${emp.calleResidencia}</div>
                                            <div class="mb-2"><span class="fw-bold">Casa:</span> ${emp.casaResidencia}</div>
                                            <div class="mb-2"><span class="fw-bold">Estado Civil:</span> ${emp.estadoCivil}</div>
                                            <div class="mb-2"><span class="fw-bold">Registro:</span> ${new Date().toISOString().slice(0,10)}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer p-2 border-0">
                                    <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;

                    // Añadir modal al body
                    const div = document.createElement('div');
                    div.innerHTML = modalHtml;
                    document.body.appendChild(div.firstElementChild);

                    // Notificar éxito y resetear formulario
                    alert(data.message || 'Empleado agregado');
                    form.reset();
                    // Cerrar modal
                    const modalEl = document.getElementById('employeeModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                } catch (err) {
                    console.error(err);
                    alert('Error de red o del servidor');
                } finally {
                    submitBtn.disabled = false;
                }
            });
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Abrir modal y cargar datos
            document.querySelectorAll('.btn-edit').forEach(function(btn) {
                btn.addEventListener('click', async function(e) {
                    const id = this.getAttribute('data-id');
                    if (!id) return;
                    try {
                        const res = await fetch('../employees/getEmployee.php?idPersonal=' + encodeURIComponent(id));
                        const json = await res.json();
                        if (!json.success) {
                            alert(json.message || 'Error al obtener empleado');
                            return;
                        }
                        const d = json.data;
                        // Rellenar formulario
                        document.getElementById('edit_idPersonal').value = d.idPersonal || '';
                        document.getElementById('edit_nombre').value = d.nombre || '';
                        document.getElementById('edit_Telefono').value = d.Telefono || '';
                        document.getElementById('edit_DUI').value = d.DUI || '';
                        document.getElementById('edit_fechaNacimiento').value = d.fechaNacimiento || '';
                        document.getElementById('edit_departamento').value = d.departamento || '';
                        document.getElementById('edit_distrito').value = d.distrito || '';
                        document.getElementById('edit_coloniaResidencia').value = d.coloniaResidencia || '';
                        document.getElementById('edit_calleResidencia').value = d.calleResidencia || '';
                        document.getElementById('edit_casaResidencia').value = d.casaResidencia || '';
                        document.getElementById('edit_estadoCivil').value = d.estadoCivil || '';
                        document.getElementById('edit_currentPhoto').textContent = d.fotografia ? 'Imagen actual: ' + d.fotografia : '';
                        // Mostrar modal
                        var modalEl = document.getElementById('editEmployeeModal');
                        var modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    } catch (err) {
                        console.error(err);
                        alert('Error de red al obtener empleado');
                    }
                });
            });

            // Enviar formulario de actualización
            document.getElementById('btnUpdateEmployee').addEventListener('click', async function() {
                const form = document.getElementById('editEmployeeForm');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const fd = new FormData(form);

                try {
                    const res = await fetch('../employees/updateEmployee.php', {
                        method: 'POST',
                        body: fd,
                        credentials: 'same-origin'
                    });
                    const json = await res.json();
                    if (!json.success) {
                        alert(json.message || 'Error al actualizar');
                        return;
                    }

                    const emp = json.data;
                    // Actualizar fila en la tabla
                    const tr = document.querySelector('tr[data-id="' + emp.idPersonal + '"]');
                    if (tr) {
                        const fotoSrc = emp.fotografia && emp.fotografia !== 'user.png' ? '../img/imgEmployees/' + emp.fotografia : '../img/user.png';
                        const nombreTd = tr.querySelector('.emp-nombre');
                        if (nombreTd) nombreTd.textContent = emp.nombre || '';

                        const duiTd = tr.querySelector('.emp-dui');
                        if (duiTd) duiTd.textContent = emp.DUI || '';

                        const deptTd = tr.querySelector('.emp-departamento');
                        if (deptTd) deptTd.textContent = emp.departamento || '';

                        const distTd = tr.querySelector('.emp-distrito');
                        if (distTd) distTd.textContent = emp.distrito || '';

                        const fotoTd = tr.querySelector('.emp-foto img');
                        if (fotoTd) fotoTd.src = fotoSrc;
                    }

                    alert(json.message || 'Empleado actualizado');
                    // Cerrar modal
                    var modalEl = document.getElementById('editEmployeeModal');
                    bootstrap.Modal.getInstance(modalEl).hide();
                } catch (err) {
                    console.error(err);
                    alert('Error de red o del servidor');
                }
            });
        });
    </script>
</body>

</html>