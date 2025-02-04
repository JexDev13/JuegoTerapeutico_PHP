<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control de Pacientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/paciente.css">
    <link rel="stylesheet" href="../css/framework.css">
    <script type="module">
        // Import the functions you need from the SDKs you need
        import {initializeApp} from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
        import {getAuth, onAuthStateChanged} from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";
        import {getFirestore, doc, getDoc} from "https://www.gstatic.com/firebasejs/11.0.1/firebase-firestore.js";

        // Your web app's Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyCx9LP5qF7Ce55QxcGIKskvppiQGYfYYL0",
            authDomain: "juegoterrapeuticoepn.firebaseapp.com",
            projectId: "juegoterrapeuticoepn",
            storageBucket: "juegoterrapeuticoepn.firebasestorage.app",
            messagingSenderId: "537931533464",
            appId: "1:537931533464:web:789d18eddff759799d14ed",
            measurementId: "G-WB9KER4NJB"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth();
        const db = getFirestore();

        // Get the therapist's name and display it
        onAuthStateChanged(auth, async (user) => {
            if (user) {
                const userId = user.uid;
                const docRef = doc(db, 'terapeutas', userId);
                const docSnap = await getDoc(docRef);

                if (docSnap.exists()) {
                    const nombre = docSnap.data().nombre;
                    document.getElementById('nombre-terapeuta').textContent = nombre;
                    localStorage.setItem('nombreTerapeuta', nombre); // Guardar en localStorage
                } else {
                    console.log("No such document!");
                }
            } else {
                console.log("No user is signed in.");
            }
        });
    </script>
</head>
<header>
    <div class="barra back-color-secondary color-primary">
        <p class="font-berlin">Saludos, <span id="nombre-terapeuta"></span></p>
        <div id="boton-cerrar-sesion" class="back-color-primary margin-0 "
             onclick="document.getElementById('enlace-cerrar-sesion').click()">
            <a id="enlace-cerrar-sesion" class="color-secondary hover-a font-impact" href="../index.php">Cerrar Sesión</a>
        </div>
    </div>
</header>
<body class="background-image margin-0">
<div class="recuadro d-flex">
    <div class="recuadro-img d-flex flex-1">
        <img src="../img/Pacientes.png" alt="">
    </div>
    <div class="recuadro-opciones-pacientes back-color-primary border-1-black d-flex flex-1 flex-direction-column hover-box-shadow">
        <h2 class="terapeuta">Selecciona una de las siguientes opciones:</h2>
        <a href="listadoPacientes.html" class="boton back-color-cuaternary color-tertiary">Ingresar cédula del paciente</a>
        <a href="registroPaciente.html" class="boton back-color-cuaternary color-tertiary">Registrar nuevo paciente</a>
    </div>
</div>
</body>
</html>