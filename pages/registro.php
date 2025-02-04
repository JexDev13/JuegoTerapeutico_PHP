<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../controllers/userAuth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);

    $auth = new UserAuth($pdo);
    $result = $auth->register($nombre, $apellido, $correo, $contrasena);

    if (isset($result['error'])) {
        echo "<script>alert('Error: " . $result['error'] . "');</script>";
    } else {
        echo "<script>alert('Registro exitoso. Redirigiendo...'); window.location.href = '../pages/inicioSesion.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/registro.css">
    <link rel="stylesheet" href="../css/framework.css">
</head>

<body class="background-image">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg px-2 border border-black" style="width: 450px;">
            <div class="card-body px-2">
                <h1 class="text-center mb-2 fs-4">Registro de Terapeuta</h1>
                <form id="registerForm" action="registro.php" method="POST">
                    <div class="mb-3">
                        <input type="text" name="nombre" id="nombre" class="form-control form-control-sm w-100 h-25"
                            style="font-size: 15px;" placeholder="Nombre" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" minlength="2"
                            maxlength="50" title="Solo se permiten letras y espacios" required>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="apellido" id="apellido" class="form-control form-control-sm w-100 h-25"
                            style="font-size: 15px;" placeholder="Apellido" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                            minlength="2" maxlength="50" title="Solo se permiten letras y espacios" required>
                    </div>

                    <div class="mb-3">
                        <input type="email" name="correo" id="correo" class="form-control form-control-sm w-100 h-25"
                            style="font-size: 15px;" placeholder="Correo electrónico"
                            pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                            title="Ingrese un correo válido (ej: usuario@dominio.com)" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" name="contrasena" id="contrasena"
                            class="form-control form-control-sm w-100 h-25" style="font-size: 15px;"
                            placeholder="Contraseña" minlength="6" maxlength="20"
                            pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,20}$"
                            title="Debe contener al menos una letra y un número, sin caracteres especiales" required>
                    </div>

                    <p id="mensaje" class="text-success text-center small" style="display: none;">Registro exitoso.
                        Redirigiendo...</p>

                    <div class="d-grid gap-2">
                        <button type="submit"
                            class="boton back-color-cuaternary color-tertiary d-flex align-items-center justify-content-center px-4 py-2 rounded hover-text-light w-100">
                            <span class="material-icons">person_add</span>
                            <span class="ms-2">Registrarse</span>
                        </button>

                        <button type="button" id="boton-r"
                            class="boton back-color-cuaternary color-tertiary d-flex align-items-center justify-content-center px-4 py-2 rounded hover-text-light w-100"
                            onclick="window.location.href='../index.php'">
                            <span class="material-icons">arrow_back</span>
                            <span class="ms-2">Regresar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


</html>