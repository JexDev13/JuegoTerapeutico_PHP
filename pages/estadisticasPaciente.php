<?php

require __DIR__ . '/../config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idUsuario'])) {
    header("Location: inicioSesion.php");
    exit();
}

unset($_SESSION['registroInsertado']);

// Obtener los pacientes disponibles para el terapeuta
$sql = "SELECT id, cedula, CONCAT(nombre, ' ', apellido) AS nombre_completo , fechaNacimiento
        FROM paciente 
        WHERE terapeutaId = :terapeutaId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':terapeutaId', $_SESSION['idUsuario'], PDO::PARAM_INT);
$stmt->execute();
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si un paciente ha sido seleccionado
if (isset($_GET['id'], $_GET['cedula'], $_GET['nombre_completo'], $_GET['edad'])) {
    $_SESSION['pacienteId'] = $_GET['id'];
    $_SESSION['cedula'] = $_GET['cedula'];
    $_SESSION['nombre_completo'] = $_GET['nombre_completo'];
    $_SESSION['edad'] = $_GET['edad'];
}

// Obtener las estadísticas del paciente con el id almacenado en la sesión
if (isset($_SESSION['pacienteId'])) {
    $sql = "
    SELECT 
        id,
        tiempoJuego,
        movimientos, 
        evaluacion, 
        tipoJuego, 
        dificultad, 
        DATE(fecha) AS fecha, 
        DATE_FORMAT(fecha, '%h:%i:%s %p') AS hora
    FROM estadisticas
    WHERE pacienteId = :pacienteId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pacienteId', $_SESSION['pacienteId'], PDO::PARAM_INT);
    $stmt->execute();
    $estadisticas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el número de juegos completos
    $numJuegosCompletados = count($estadisticas);
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
                    $_SESSION['pacienteId'] = $paciente['id'];
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
                        <p><strong>Juegos Completados: </strong><span
                                id="total-juegos"><?php echo $numJuegosCompletados; ?></span></p>
                    </div>
                </div>

                <a href="tableroJuegos.php"
                    class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-25 text-decoration-none border-1-black"
                    style="font-family: sans-serif;">
                    <span class="material-icons">arrow_back</span>
                    <span class="ms-2">Regresar</span>
                </a>
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
                            <th>Juego</th>
                            <th>Dificultad</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estadisticas as $index => $estadistica): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $estadistica['movimientos']; ?></td>
                                <td><?php echo $estadistica['tiempoJuego']; ?></td>
                                <td><?php echo $estadistica['evaluacion']; ?></td>
                                <td><?php echo $estadistica['tipoJuego']; ?></td>
                                <td><?php echo $estadistica['dificultad']; ?></td>
                                <td><?php echo $estadistica['fecha']; ?></td>
                                <td><?php echo $estadistica['hora']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>

</html>