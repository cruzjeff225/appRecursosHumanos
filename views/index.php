<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Inicio de sesión</title>
</head>
<body>
<div class="vh-100 d-flex justify-content-center align-items-center" style="background-color: #f4f6f9;">
    <div class="card shadow-lg p-5" style="width: 100%; max-width: 450px; border: none;">
        <div class="text-center mb-4">
            <img src="../img/rrhh.png" alt="Logo Recursos Humanos" style="width: 80px; height: auto;">
        </div>
        <h2 class="text-center fw-bold mb-3" style="color: #343a40;">Sistema de Recursos Humanos</h2>
        <p class="text-center text-muted mb-4">Por favor, ingresa tus credenciales para acceder</p>
        <form action="../controllers/validation.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label fw-bold" style="color: #343a40;">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label fw-bold" style="color: #343a40;">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </div>
        </form>
        <div class="text-center mt-4">

        </div>
    </div>
</div>
</body>
</html>
