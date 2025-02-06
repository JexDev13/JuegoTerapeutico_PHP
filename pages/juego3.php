<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width = device-width, initial-scale=1.0">
    <title>Ordenar palabras por longitud</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/juego.css">
    <link rel="stylesheet" href="../css/framework.css">
</head>

<body class="background-image margin-0 d-flex flex-column" style="min-height: 100vh; padding: 50px 75px;">
    <div class="d-flex flex-column justify-content-center align-items-center" style="width: 100%;">
        <div class="mb-3 p-4 font-hartsfolk w-100">
            <h1 class="text-center mb-2 font-hartsfolk">Ordena los objetos por la palabra más corta a la más larga.</h1>
            <h2 class="text-start mb-4 fs-4">Arrastra y suelta los objetos en el orden correcto.</h2>
            <div class="d-flex justify-content-between align-items-center w-100">
                <button id="pausarBtn" class="btn btn-warning mt-3 d-flex justify-content-center align-items-center">
                    Pausar
                </button>
                <p id="contador" class="font-woliu">Movimientos: 0</p>
                <p id="cronometro" class="font-woliu">Tiempo: 00:00</p>

            </div>
        </div>

        <div class="game-area d-flex justify-content-between align-items-center w-75 h-100" id="gameArea1">

        </div>

        <div class="card" style="width: 75%;">
            <div class="card-header font-hartsfolk">
                <h3 class="fs-4 text-center ">Zona de Arrastre</h3>
                <p class="text-start fs-5">Arrastra aquí las palabras</p>
            </div>
            <div class="game-area  d-flex justify-content-between p-5" id="dropArea">
                <div class="object border-1-black rounded" style="min-height: 50px;" draggable="true" id="bloque1"
                    style="margin-right: 5px;"></div>
                <div class="object border-1-black rounded" style="min-height: 50px;" draggable="true" id="bloque2"
                    style="margin-right: 5px;"></div>
                <div class="object border-1-black rounded" style="min-height: 50px;" draggable="true" id="bloque3"
                    style="margin-right: 5px;"></div>
                <div class="object border-1-black rounded" style="min-height: 50px;" draggable="true" id="bloque4"
                    style="margin-right: 5px;"></div>
                <div class="object border-1-black rounded" style="min-height: 50px;" draggable="true" id="bloque5">
                </div>
            </div>
        </div>


    </div>

    <!-- Mensaje de feedback -->
    <div class="message" id="message"></div>

    <div id="pausa" class="modal" style="display: none;">
        <div class="modal_content">
            <h2>El juego está en pausa</h2>
            <p id="tiempo">Tiempo transcurrido: 00:00</p>
            <button onclick="volverAlJuego()">Volver al Juego</button>
            <button id="botonSalirJuego" onclick="salirDelJuego()">Salir</button>

        </div>
    </div>
    <script src="../js/juego3.js?v=<?php echo time(); ?>"></script>


</body>

</html>