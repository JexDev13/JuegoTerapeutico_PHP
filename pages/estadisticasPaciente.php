<?php

require __DIR__ . '/../config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idUsuario'])) {
    header("Location: inicioSesion.php");
    exit();
}

// Obtener los pacientes disponibles para el terapeuta
$sql = "SELECT id, cedula, CONCAT(nombre, ' ', apellido) AS nombre_completo , fechaNacimiento
        FROM paciente 
        WHERE terapeutaId = :terapeutaId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':terapeutaId', $_SESSION['idUsuario'], PDO::PARAM_INT);
$stmt->execute();
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si un paciente ha sido seleccionado
if (isset($_GET['cedula'], $_GET['nombre_completo'], $_GET['edad'])) {
    $_SESSION['cedula'] = $_GET['cedula'];
    $_SESSION['nombre_completo'] = $_GET['nombre_completo'];
    $_SESSION['edad'] = $_GET['edad'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas del paciente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/estadisticasPaciente.css">
    <link rel="stylesheet" href="../css/tableroJuegos.css">
    <link rel="stylesheet" href="../css/framework.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php include __DIR__ . '/../components/menu.php'; ?>

<body class="background-image margin-0 d-flex flex-column" style="min-height: 100vh;">
    <div class="p-4 flex-grow-1" style="width: 100%;">
        <h2 class="text-center mb-2 font-hartsfolk">Estadísticas del paciente</h2>
        <?php
        // Verifica si la cédula fue seleccionada
        if (isset($_GET['cedula'])) {
            $cedula = $_GET['cedula'];
            foreach ($pacientes as $paciente) {
                if ($paciente['cedula'] == $cedula) {
                    $fechaNacimiento = new DateTime($paciente['fechaNacimiento']);
                    $hoy = new DateTime();
                    $edad = $hoy->diff($fechaNacimiento)->y;
                    $_SESSION['cedula'] = $paciente['cedula'];
                    $_SESSION['nombre_completo'] = $paciente['nombre_completo'];
                    $_SESSION['edad'] = $edad;
                    break;
                }
            }
        }
        ?>

        <!-- Selección de paciente -->
        <?php if (empty($_SESSION['cedula'])): ?>
            <div class="border border-0 mb-3 p-2 font-hartsfolk" style="width: 30%;">
                <h3 class="mb-3 px-2 text-left">Seleccionar Paciente</h3>
                <div class="card-body">
                    <form action="estadisticasPaciente.php" method="get">
                        <select name="cedula" class="form-select" style="font-family: sans-serif;" required>
                            <option value="" disabled selected>Selecciona un paciente</option>
                            <?php foreach ($pacientes as $paciente): ?>
                                <option value="<?php echo $paciente['cedula']; ?>"><?php echo $paciente['nombre_completo']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit"
                            class="boton mt-3 back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100"
                            style="font-family: sans-serif; padding: 0;">
                            <span class="material-icons">format_list_bulleted</span>
                            <span class="ms-2 text-nowrap">Seleccionar Paciente</span>
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- Sección 1 -->
            <div class="mb-3">
                <!-- Datos del paciente -->
                <div class="border border-0 mb-2 p-2 font-hartsfolk" style="width: 50%;">
                    <h3 class="card-title text-left mb-2">Datos del paciente</h3>
                    <div class="card-body">
                        <p><strong>Cédula:</strong> <?php echo $_SESSION['cedula'] ?? 'No disponible'; ?></p>
                        <p><strong>Nombre:</strong> <?php echo $_SESSION['nombre_completo'] ?? 'No disponible'; ?></p>
                        <p><strong>Edad:</strong> <?php echo $_SESSION['edad'] ?? 'No disponible'; ?></p>
                        <p><strong>Juegos Completados: </strong><span id="total-juegos">0</span></p>
                    </div>
                </div>

                <button type="button" id="boton-r"
                    class="boton back-color-cuaternary color-tertiary d-flex align-items-center justify-content-center px-4 py-2 rounded hover-text-light w-25"
                    onclick="window.history.back();">
                    <span class="material-icons">arrow_back</span>
                    <span class="ms-2">Regresar</span>
                </button>
            </div>
            <!-- Sección 2 -->
            <div>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Movimientos</th>
                            <th>Tiempo</th>
                            <th>Rango de calidad</th>
                            <th>Número de juego</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aqui va los datos de la tabla-->
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>

</html>