<?php
session_start();

// Verificar si la variable de sesión 'cedula' no está definida o está vacía
if (empty($_SESSION['cedula'])) {
    header('Location: paciente.php');
    exit(); // Asegura que el código se detenga aquí
}

if (empty($_SESSION['pacienteId'])) {
    header('Location: paciente.php');
    exit(); // Asegura que el código se detenga aquí
}

// Verificar si el registro ya fue insertado para evitar duplicación
if (isset($_SESSION['registroInsertado']) && $_SESSION['registroInsertado'] === true) {
    // Redirigir a tableroJuegos.php si ya se insertó el registro
    header('Location: tableroJuegos.php');
    exit();
}

// Marcar que el registro ha sido insertado
$_SESSION['registroInsertado'] = false;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Felicidades!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/ventanaGanadora.css">
    <link rel="stylesheet" href="../css/framework.css">
</head>

<body>
    <div class="ganador">
        <div class="text-center" style="background-color: rgb(250, 235, 215); border-radius: 8px; padding: 25px;">
            <h1 class="text-success" style="font-family:ChristmasLaugh;">¡Felicidades!</h1>
            <div class="text-start">
                <p> Has ordenado correctamente todas las palabras</p>
                <p id="tipoJuego"></p>
                <p id="tipoDificultad"></p>
                <p id="totalMovimientos"></p>
                <p id="tiempoJuego"></p>
                <p id="evaluacion"></p>
            </div>
            <div class="d-flex flex-column text-start mb-3">
                <a href="estadisticasPaciente.php"
                    class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black"
                    style="font-family: sans-serif;">
                    <span class="material-icons">bar_chart</span>
                    <span class="ms-2">Ver registro de partidas</span>
                </a>
                <a href="tableroJuegos.php"
                    class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black"
                    style="font-family: sans-serif;">
                    <span class="material-icons">add</span>
                    <span class="ms-2">Otros juegos</span>
                </a>
                <a href="paciente.php"
                    class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-100 text-decoration-none border-1-black"
                    style="font-family: sans-serif;">
                    <span class="material-icons">list</span>
                    <span class="ms-2">Seleccionar un paciente</span>
                </a>
                <div id="mensajeExito" style="display: none; font-size: 15px; margin-top: 10px; color: green;">
                    Registro insertado con éxito.
                </div>
            </div>
        </div>
    </div>

    <script>
        // Obtener los parámetros de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const movimientos = parseInt(urlParams.get('movimientos'));
        const tiempo = urlParams.get('tiempo');
        const juego = urlParams.get('juego');
        const dificultad = urlParams.get('dificultad');

        // Mostrar los datos en la página
        document.getElementById('tipoJuego').innerText = `Juego: ${juego }`;
        document.getElementById('tipoDificultad').innerText = `Nivel de dificultad: ${dificultad}`;
        document.getElementById('totalMovimientos').innerText = `Total movimientos: ${movimientos}`;
        document.getElementById('tiempoJuego').innerText = `Tiempo de juego: ${tiempo}`;

        // Evaluación según los movimientos
        let evaluacion = '';
        if (movimientos <= 9) {
            evaluacion = "Excelente";
        } else if (movimientos <= 15) {
            evaluacion = "Regular";
        } else {
            evaluacion = "Pésimo";
        }

        // Mostrar la evaluación
        document.getElementById('evaluacion').innerText = `Evaluación: ${evaluacion}`;

        // Realizar la inserción automática de los datos en la base de datos
        $(document).ready(function () {
            $.ajax({
                url: '../controllers/ganador_crud.php',
                type: 'POST',
                data: {
                    accion: 'crear',
                    juego: juego,
                    dificultad: dificultad,
                    tiempo: tiempo,
                    movimientos: movimientos,
                    evaluacion: evaluacion
                },
                success: function (response) {
                    if (response.status === 'success') {
                        console.log('Registro insertado con éxito');
                        $('#mensajeExito').show();
                        // Marcar que el registro ha sido insertado
                        <?php $_SESSION['registroInsertado'] = true; ?>
                    } else {
                        console.error('Error al insertar registro:', response.message);
                    }
                },
                error: function () {
                    console.error('Error en la solicitud AJAX');
                }
            });
        });
    </script>
</body>

</html>