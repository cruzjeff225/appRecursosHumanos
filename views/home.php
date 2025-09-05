<?php
// Guardar la sesión y verificar si el usuario ha iniciado sesión
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Home hero */
        .home-hero-outer {
            width: 100vw;
            min-height: 100vh;
            background: #fff;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        @media (max-width: 991.98px) {
            .home-hero-outer {
                left: 0;
                right: 0;
                margin-left: 0;
                margin-right: 0;
            }
        }

        .home-avatar {
            width: 80px;
            height: 80px;
            background: #b6c3c5ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 12px rgba(255, 224, 102, 0.15);
        }

        .home-card {
            transition: transform 0.18s, box-shadow 0.18s;
            border-radius: 1.25rem;
            background: var(--primary-white);
        }

        .home-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 8px 32px rgba(255, 240, 180, 0.13);
            text-decoration: none;
        }
    </style>
    <title>Inicio</title>
</head>
<div class="home-hero-outer min-vh-100 d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="text-center mb-5">
            <div class="home-avatar mx-auto mb-3">
                <i class="fa fa-address-card fa-3x text-white"></i>
            </div>
            <h1 class="display-3 fw-bold mb-2 text-primary">Bienvenido</h1>
            <p class="lead text-secondary mb-0">Sistema de Gestión de Recursos Humanos</p>
        </div>

        <div class="row g-4 w-100 justify-content-center">
            <div class="col-12 col-md-6 col-lg-3">
                <a class="home-card card border-0 shadow-lg text-decoration-none h-100" a href="../views/usuarios.php">
                    <div class="card-body text-center py-4">
                        <i class="fa fa-user fa-2x text-success mb-3"></i>
                        <h5 class="card-title fw-bold mb-2 text-dark">Gestión de Usuarios</h5>
                        <p class="card-text text-secondary mb-0">Consulta, edita y administra usuarios.</p>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a class="home-card card border-0 shadow-lg text-decoration-none h-100" a href="../views/personal.php">
                    <div class="card-body text-center py-4">
                        <i class="fa fa-users fa-2x text-primary mb-3"></i>
                        <h5 class="card-title fw-bold mb-2 text-dark">Gestión de Talento Humano</h5>
                        <p class="card-text text-secondary mb-0">Consulta, edita y administra usuarios.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<body>
    <?php
    include_once('../views/nav.php');
    ?>
</body>

</html>