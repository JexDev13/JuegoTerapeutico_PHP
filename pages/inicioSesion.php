<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../controllers/userAuth.php';


session_start();

if (isset($_SESSION['idUsuario'])) {
    header("Location: paciente.php");
    exit();
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);

    $auth = new UserAuth($pdo);
    $result = $auth->login($correo, $contrasena);

    if (isset($result['error'])) {
        $error_message = $result['error'];
    } else {
        echo "<script>alert('Inicio de sesión exitoso. Redirigiendo...'); window.location.href = 'paciente.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/inicioSesion.css">
    <link rel="stylesheet" href="../css/framework.css">
</head>

<body class="background-image">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg px-2 border border-black" style="width: 450px;">
            <div class="card-body px-2">
                <h1 class="text-center mb-3 fs-4">Iniciar Sesión</h1>
                <div id="img-login" class="d-flex justify-content-center align-items-center">
                    <img src="../img/Login%20Persona.png" alt="Imagen de login" class="img-fluid"
                        style="width: auto; height: 150px;">
                </div>
                <form id="login-form" method="POST" action="inicioSesion.php">
                    <div class="mb-3">
                        <input type="email" name="correo" id="correo" class="form-control form-control-sm w-100 h-25"
                            style="font-size: 15px;" placeholder="Correo electrónico"
                            pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                            title="Ingrese un correo válido (ej: usuario@dominio.com)" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" name="contrasena" id="contrasena"
                            class="form-control form-control-sm w-100 h-25" style="font-size: 15px;"
                            placeholder="Contraseña" required>
                    </div>


                    <?php if ($error_message): ?>
                        <p class="text-danger text-center small"><?php echo $error_message; ?></p>
                    <?php endif; ?>

                    <div class="d-grid gap-2">
                        <button type="submit"
                            class="boton back-color-cuaternary color-tertiary d-flex align-items-center justify-content-center px-4 py-2 rounded hover-text-light w-100">
                            <span class="material-icons">login</span>
                            <span class="ms-2">Iniciar Sesión</span>
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
</body>>

</html>