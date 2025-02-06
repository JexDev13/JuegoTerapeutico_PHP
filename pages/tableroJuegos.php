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

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tablero de Juegos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/tableroJuegos.css">
    <link rel="stylesheet" href="../css/framework.css">
</head>

<?php include __DIR__ . '/../components/menu.php'; ?>

<body class="background-image margin-0 font-hartsfolk d-flex flex-column" style="min-height: 100vh;">
    <div class="p-4 flex-grow-1" style="width: 100%;">
        <h2 class="text-center mb-2 font-hartsfolk">Juegos del paciente</h2>
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
                    <form action="tableroJuegos.php" method="get">
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

            <!-- Datos del paciente -->
            <div class="border border-0 mb-3 p-2 font-hartsfolk" style="width: 30%;">
                <h3 class="card-title text-left mb-2">Datos del paciente</h3>
                <div class="card-body">
                    <p><strong>Cédula:</strong> <?php echo $_SESSION['cedula'] ?? 'No disponible'; ?></p>
                    <p><strong>Nombre:</strong> <?php echo $_SESSION['nombre_completo'] ?? 'No disponible'; ?></p>
                    <p><strong>Edad:</strong> <?php echo $_SESSION['edad'] ?? 'No disponible'; ?></p>
                    <a href="estadisticasPaciente.php"
                        class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black"
                        style="font-family: sans-serif;">
                        <span class="material-icons">bar_chart</span>
                        <span class="ms-2">Estadísticas del paciente</span>
                    </a>
                </div>
            </div>

            <div>
                <h2 class="mb-2 p-2 text-left">
                    Tablero de Juegos
                </h2>
                <div class="row mb-3">
                    <!-- Modal Juego 1 -->
                    <div class="modal fade" id="modalJuego1" tabindex="-1" aria-labelledby="modalJuego1Label"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalJuego1Label">Nivel de Dificultad - Juego 1</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <button
                                        class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black mb-2"
                                        style="font-family: sans-serif;" data-bs-dismiss="modal" aria-label="Close"
                                        onclick="location.href='juego1Asistido.php'">
                                        <span class="material-icons">sentiment_very_satisfied</span>
                                        <span class="ms-2">Jugar con pesos</span>
                                    </button>

                                    <button
                                        class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black"
                                        style="font-family: sans-serif;" data-bs-dismiss="modal" aria-label="Close"
                                        onclick="location.href='juego1.php'">
                                        <span class="material-icons">sentiment_satisfied</span>
                                        <span class="ms-2">Jugar sin pesos</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Juego 2 -->
                    <div class="modal fade" id="modalJuego2" tabindex="-1" aria-labelledby="modalJuego2Label"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalJuego2Label">Nivel de Dificultad - Juego 2</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <button
                                        class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black mb-2"
                                        style="font-family: sans-serif;" data-bs-dismiss="modal" aria-label="Close"
                                        onclick="location.href='juego2Asistido.php'">
                                        <span class="material-icons">sentiment_very_satisfied</span>
                                        <span class="ms-2">Jugar con pesos</span>
                                    </button>

                                    <button
                                        class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black"
                                        style="font-family: sans-serif;" data-bs-dismiss="modal" aria-label="Close"
                                        
                                        onclick="location.href='juego2.php'">
                                        <span class="material-icons">sentiment_satisfied</span>
                                        <span class="ms-2">Jugar sin pesos</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal Juego 3 -->
                    <div class="modal fade" id="modalJuego3" tabindex="-1" aria-labelledby="modalJuego3Label"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalJuego3Label">Nivel de Dificultad - Juego 3</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <button
                                        class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black"
                                        style="font-family: sans-serif;" data-bs-dismiss="modal" aria-label="Close"
                                        onclick="location.href='juego3.php'">
                                        <span class="material-icons">sentiment_satisfied</span>
                                        <span class="ms-2">Jugar sin pistas</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Juego 1 -->
                    <div class="col-md-4 mb-3">
                        <div class="card-body bg-white p-2 border-1-black rounded">
                            <h4 class="text-center" style="font-size: 20px;">Juego 1: De liviano a pesado</h4>
                            <img src="../img/Ejemplo_juego_1.jpg" alt=""
                                style="width: auto; height: 400px; display: block; margin: 0 auto;" class="mb-2">
                            <p class="text-left">Ordena objetos del más liviano al más pesado.</p>
                            <button
                                class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black"
                                style="font-family: sans-serif;" data-bs-toggle="modal" data-bs-target="#modalJuego1">
                                <span class="material-icons">play_arrow</span>
                                <span class="ms-2">Jugar</span>
                            </button>
                        </div>
                    </div>

                    <!-- Juego 2 -->
                    <div class="col-md-4 mb-3">
                        <div class="card-body bg-white p-2 border-1-black rounded">
                            <h4 class="text-center" style="font-size: 20px;">Juego 2: De pesado a liviano</h4>
                            <img src="../img/Ejemplo_juego_2.jpg" alt=""
                                style="width: auto; height: 400px; display: block; margin: 0 auto;" class="mb-2">
                            <p class="text-left">Ordena objetos del más pesado al más liviano.</p>
                            <button
                                class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black"
                                style="font-family: sans-serif;" data-bs-toggle="modal" data-bs-target="#modalJuego2">
                                <span class="material-icons">play_arrow</span>
                                <span class="ms-2">Jugar</span>
                            </button>
                        </div>
                    </div>

                    <!-- Juego 3 -->
                    <div class="col-md-4 mb-3">
                        <div class="card-body bg-white p-2 border-1-black rounded">
                            <h4 class="text-center" style="font-size: 20px;">Juego 3: Ordena palabras</h4>
                            <img src="../img/Ejemplo_juego_3.jpg" alt=""
                                style="width: auto; height: 400px; display: block; margin: 0 auto;" class="mb-2">
                            <p class="text-left">Ordena palabras de las más corta a la más larga.</p>
                            <button
                                class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black"
                                style="font-family: sans-serif;" data-bs-toggle="modal" data-bs-target="#modalJuego3">
                                <span class="material-icons">play_arrow</span>
                                <span class="ms-2">Jugar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>

</html>