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
    $idSesion = $_SESSION['idUsuario']; // ID del usuario logueado
    // Lógica de Paginación
    $registrosPorPágina = 10;
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($paginaActual - 1) * $registrosPorPágina;

    // Contar total de usuarios (excluyendo el usuario logueado)
    $totalUsuariosQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM usuarios WHERE idUsuario != '$idSesion'");
    $totalUsuariosRow = mysqli_fetch_assoc($totalUsuariosQuery)['total'];
    $totalPaginas = ceil($totalUsuariosRow / $registrosPorPágina);


    // Consulta SQL para obtener usuarios
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
        $search = mysqli_real_escape_string($con, $_POST['search']);
        $query = "SELECT * FROM usuarios WHERE (nombreUsuario LIKE '%$search%' OR email LIKE '%$search%') AND idUsuario != '$idSesion'";
    } else {
        // Si no hay búsqueda, obtener todos los usuarios (excluyendo el usuario logueado y aplicando paginación)
        $query = "SELECT * FROM usuarios WHERE idUsuario != '$idSesion' LIMIT $registrosPorPágina OFFSET $offset";
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
            <button id="btnNewUser" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#userModal">
                <i class="fas fa-user-plus"></i> Nuevo Usuario
            </button>
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
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Acción</th>
                        <th>Contraseña</th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
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
    </div>

    <!-- Modal: Nuevo Usuario -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Registrar Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm" class="row g-3" enctype="multipart/form-data">
                        <!-- Usuario -->
                        <div class="col-md-6">
                            <label for="nombreUsuario" class="form-label">Nombre de Usuario</label>
                            <input type="text"
                                class="form-control"
                                id="nombreUsuario"
                                name="nombreUsuario"
                                placeholder="Ingresa el nombre de usuario"
                                pattern="[A-Za-z0-9]{4,10}"
                                title="El usuario debe tener entre 4 y 10 caracteres, solo letras y números"
                                required>
                        </div>
                        <!-- Correo -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Ingresa un correo válido"
                                pattern="[A-Za-z0-9.!#$%&'*+/=?^_`{|}~-]+@[A-Za-z0-9-]+(?:\.[A-Za-z0-9-]+)*\.[A-Za-z]{2,}"
                                title="Introduce un correo válido, por ejemplo: usuario@ejemplo.com"
                                required>
                        </div>
                        <!-- Rol de Usuario -->
                        <div class="col-md-6">
                            <label for="rolId" class="form-label">Rol de Usuario</label>
                            <select id="rolId" class="form-select" name="rolId">
                                <option selected disabled value="">Seleccionar...</option>
                                <option value="1">admin</option>
                                <option value="2">superadmin</option>
                            </select>
                        </div>
                        <!-- Contraseña -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="*********"
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$"
                                title="Debe tener entre 8 y 16 caracteres, incluir al menos una mayúscula, una minúscula y un número"
                                required>
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" id="btnSubmitUser" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php mysqli_close($con); ?>

    <!-- Bootstrap Bundle JS (necesario para modales) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const form = document.getElementById('userForm');
            const submitBtn = document.getElementById('btnSubmitUser');

            // Capturar la tabla y su cuerpo
            const table = document.querySelector('table.table');
            const tbody = table ? table.querySelector('tbody') : null;

            if (!form || !submitBtn) return;

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
                    const res = await fetch('../user/addUser.php', {
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

                    // Insertar nueva fila en la tabla
                    const emp = data.data || {};

                    // Nuevo indice
                    const lastIndexCell = table.querySelectorAll('tbody tr td:first-child');
                    const newIndex = (lastIndexCell.length ? lastIndexCell.length + 1 : table.querySelectorAll('tr').length);

                    // Crear nueva fila
                    const tr = document.createElement('tr');
                    tr.className = 'align-middle';
                    tr.innerHTML = `
                        <td>${newIndex}</td>
                        <td>${emp.nombreUsuario || ''}</td>
                        <td>${emp.email || ''}</td>
                        <td>
                            <div class="d-flex  align-items-center gap-2">
                                <a href="../user/editUser.php?idUsuario=${emp.idUsuario || ''}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="../user/deleteUser.php" method="POST" onsubmit="return confirm('¿Estás seguro que deseas eliminar este usuario?');" class="m-0 p-0">
                                    <input type="hidden" name="idUsuario" value="${emp.idUsuario || ''}">
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                        <td>
                            <a href="../user/editPassword.php?idUsuario=${emp.idUsuario || ''}" class="btn btn-warning btn-sm">
                                <i class="fas fa-key"></i>
                            </a>
                        </td>
                   `;

                    // Agregar la fila al tbody o tabla
                    if (tbody) tbody.appendChild(tr);
                    else table.appendChild(tr);

                    // Mostrar mensaje de éxito
                    alert(data.message || 'Usuario registrado exitosamente.');

                    // Cerrar el modal
                    const modalEl = document.getElementById('userModal');
                    if (modalEl) {
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.hide();
                    }

                    // Resetear el formulario
                    form.reset();

                } catch (err) {
                    console.log(err);
                    alert('Error de red o del servidor.');
                } finally {
                    submitBtn.disabled = false;
                }
            })
        })();
    </script>
</body>

</html>