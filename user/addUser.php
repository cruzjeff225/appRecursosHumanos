<?php
session_start();
if (isset($_SESSION['usuario']) == null) {
    header("Location: ../views/index.php");
}
include_once '../config/config.php';
// vAriables para capturar los datos del formulario
$nombreUsuario = isset($_POST['nombreUsuario']) ? $_POST['nombreUsuario'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
$rol = isset($_POST['rolId']) ? $_POST['rolId'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Agregar Usuario</title>
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
                <div class="col-md-12">
                    <label for="nombreUsuario" class="form-label fw-light">Usuario</label>
                    <input type="text" class="form-control rounded-pill" id="nombreUsuario" name="nombreUsuario" placeholder="Ingresa el nombre de usuario" required value="<?php echo $nombreUsuario ?>">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="email" class="form-label fw-light">Correo</label>
                    <input type="email" class="form-control rounded-pill" id="email" name="email" placeholder="Ingresa un correo válido" required value="<?php echo $email ?>">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="password" class="form-label fw-light">Contraseña</label>
                    <input type="password" class="form-control rounded-pill" id="password" name="password" placeholder="*********" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="rolId" class="form-label fw-light">Rol de Usuario</label>
                    <select class="form-select form-select-sm" name="rolId" id="rolId" aria-label=".form-select-sm example" require>
                        <option value="" selected disabled>Seleccione Rol</option>
                        <?php
                        // Consulta roles desde la base de datos
                        $sqlRoles = "SELECT IdRol, Rol FROM Rol";
                        $resultRoles = mysqli_query($con, $sqlRoles);

                        while ($row = mysqli_fetch_assoc($resultRoles)) {
                            // Mantener seleccionado el valor si ya fue enviado en POST
                            $selected = ($rol == $row['IdRol']) ? 'selected' : '';
                            echo '<option value="' . $row['IdRol'] . '" ' . $selected . '>' . $row['Rol'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                    <i class="fas fa-save"></i> Registrar
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
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $rol = isset($_POST['rolId']) ? $_POST['rolId'] : '';
    // Hashear la contraseña
    $passwordHash = md5($password);

    // Verificar si el nombre de usuario ya existe
    $verificar_usuario = "SELECT * FROM usuarios WHERE nombreUsuario='$nombreUsuario' OR email='$email'";
    $resultado = mysqli_query($con, $verificar_usuario);
    if (mysqli_num_rows($resultado) >=1 ) {
        echo "<script>alert('El nombre de usuario ya existe. Prueba con otro.'); window.location.href = 'addUser.php';</script>";
    } else {
        // Insertar nuevo usuario en la base de datos
        $insertar = "INSERT INTO usuarios (nombreUsuario, email, password, rolId) VALUES ('$nombreUsuario', '$email', '$passwordHash', '$rol')";
        if (mysqli_query($con, $insertar)) {
            echo "<script>alert('Usuario registrado exitosamente.'); window.location.href = '../views/usuarios.php';</script>";
        } else {
            echo "<script>alert('Error al registrar el usuario: " . mysqli_error($con) . "'); window.location.href = 'addUser.php';</script>";
        }
    }
    // Cerrar la conexión a la base de datos
    mysqli_close($con);
}
?>
