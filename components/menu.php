<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_name = isset($_SESSION['user_nombre']) ? $_SESSION['user_nombre'] : "Terapeuta";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/menu.css">
</head>

<nav class="navbar navbar-expand-lg navbar-light back-color-secondary w-100 h-100 font-berlin p-0">
    <div class="container-fluid">
        <span class="navbar-text text-white fs-1 w-85" style="margin-left: 10px;">
            Saludos, <?php echo htmlspecialchars($user_name); ?>
        </span>
        <div class="collapse navbar-collapse justify-content-end font-impact" id="navbarNav">
            <ul class="navbar-nav w-15">
                <li class="nav-item d-flex align-items-center flex-grow-1">
                    <a class="nav-link color-primary fs-4" href="juegos.php">Juegos</a>
                </li>
                <li class="nav-item d-flex align-items-center flex-grow-1">
                    <a class="nav-link color-primary fs-4" href="pacientes.php">Pacientes</a>
                </li>
                <li class="nav-item d-flex align-items-center flex-grow-1">
                    <a class="nav-link color-primary fs-4" href="../controllers/userAuth.php?action=logout">Cerrar
                        sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>