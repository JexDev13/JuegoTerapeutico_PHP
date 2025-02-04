<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acerca de OrderLogic</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/acercaDe.css">
    <link rel="stylesheet" href="../css/framework.css">
</head>

<body class="background-image d-flex flex-column align-items-center justify-content-center min-vh-100">

    <div class="container text-center">
        <div class="p-4 rounded">
            <h1 class="font-hartsfolk back-color-secondary fw-bold display-4 text-white">Acerca de OrderLogic</h1>
            <p class="mt-3">MemoryLogic es una página web basada en el folleto de estimulación cognitiva de TASE, con el
                objetivo de fortalecer el músculo cerebral de nuestros visitantes.</p>

            <h2 class="text-secondary mt-2 fs-1">¿En qué consiste?</h2>
            <p>Es una página que permite crear una cuenta a un terapeuta o a un cuidador de pacientes diagnosticados con
                Alzheimer, para que puedan acceder a 3 juegos.</p>
            <p>Una vez se tiene una cuenta creada, puede registrar a sus pacientes, para registrar sus puntuaciones en
                los juegos.</p>
            <p>Por último, los juegos son para los pacientes y les permiten fortalecer su mente, al practicar el
                ordenamiento de palabras u objetos.</p>

            <button id="boton-r"
                class="boton back-color-cuaternary color-tertiary mt-4 d-flex align-items-center mx-auto px-4 py-2 rounded hover-text-light">
                <span class="material-icons me-2">arrow_back</span> Regresar
            </button>
        </div>
    </div>

    <script>
        document.getElementById('boton-r').addEventListener('click', function () {
            window.location.href = "../index.php";
        });
    </script>

</body>

</html>