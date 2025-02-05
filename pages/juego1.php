<!DOCTYPE html>
<html lang="es">

<head>
    <!-- para qye se la pagina se ajuste al dispositivo-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width = device-width, initial-scale=1.0">
    <!-- colocar un mejor nombre -->
    <title>Ordena objetos por pesos</title>
    <!-- estilos de la pagina-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/juego.css">
    <link rel="stylesheet" href="../css/framework.css">
</head>

<body class="background-image margin-0 d-flex flex-column" style="min-height: 100vh; padding: 0 20px;">
    <div class="p-4 flex-grow-1" style="width: 100%;">
        <h1 class="text-center mb-2 font-hartsfolk">Ordena los objetos del más liviano al más pesado.</h1>
        <div class="border border-light rounded mb-3 p-4 bg-light shadow-sm font-hartsfolk">
            <h2 class="d-flex justify-content-start  mb-4">Arrastra y suelta los objetos en el orden correcto.</h2>
            <div class="d-flex justify-content-start align-items-center" style="padding-left: 30px">
                <button id="pausarBtn" class="btn btn-outline-primary px-4 py-2"
                    style="margin-right: 150px">Pausar</button>
                <p id="contador" class="mb-0 text-muted" style="margin-right: 150px">Movimientos: 0</p>
                <p id="cronometro" class="mb-0 text-muted" style="margin-right: 150px">Tiempo: 00:00</p>
            </div>
        </div>

        <!-- Área de juego donde se mostrarán los objetos -->
        <div class="game-area" id="gameArea1">
            <div class="object" draggable="true" id="item1" data-weight="0.01">Papel</div>
            <div class="object" draggable="true" id="item2" data-weight="0.05">Cortina</div>
            <div class="object" draggable="true" id="item3" data-weight="0.03">Globo</div>
            <div class="object" draggable="true" id="item4" data-weight="0.8">Zapato</div>
            <div class="object" draggable="true" id="item5" data-weight="0.25">Teléfono</div>
            <div class="object" draggable="true" id="item6" data-weight="1.2">Cobija</div>
            <div class="object" draggable="true" id="item7" data-weight="0.02">Esfero</div>
            <div class="object" draggable="true" id="item8" data-weight="0.6">Almohada</div>
            <div class="object" draggable="true" id="item9" data-weight="0.22">Pera</div>
            <div class="object" draggable="true" id="item10" data-weight="0.4">Flauta</div>
            <div class="object" draggable="true" id="item11" data-weight="0.1">Tenedor</div>
            <div class="object" draggable="true" id="item12" data-weight="0.18">Bolillo</div>
            <div class="object" draggable="true" id="item13" data-weight="0.001">Alfiler</div>
            <div class="object" draggable="true" id="item14" data-weight="2.0">Licuadora</div>
            <div class="object" draggable="true" id="item15" data-weight="0.005">Grosella</div>
            <div class="object" draggable="true" id="item16" data-weight="0.3">Guantes</div>
            <div class="object" draggable="true" id="item17" data-weight="0.7">Libreta</div>
            <div class="object" draggable="true" id="item18" data-weight="0.35">Foco</div>
            <div class="object" draggable="true" id="item19" data-weight="3.0">Guitarra</div>
            <div class="object" draggable="true" id="item20" data-weight="0.21">Reloj</div>
            <div class="object" draggable="true" id="item21" data-weight="7000.0">Autobús</div>
            <div class="object" draggable="true" id="item22" data-weight="0.015">Arcillos</div>
            <div class="object" draggable="true" id="item23" data-weight="1.8">Sandía</div>
            <div class="object" draggable="true" id="item24" data-weight="0.12">Diadema</div>
            <div class="object" draggable="true" id="item25" data-weight="4.5">Calabaza</div>
            <div class="object" draggable="true" id="item26" data-weight="0.37">Taza</div>
            <div class="object" draggable="true" id="item27" data-weight="0.55">Tijeras</div>
            <div class="object" draggable="true" id="item28" data-weight="0.008">Algodón</div>
            <div class="object" draggable="true" id="item29" data-weight="0.45">Cartera</div>
            <div class="object" draggable="true" id="item30" data-weight="0.2">Kiwi</div>
            <div class="object" draggable="true" id="item31" data-weight="0.09">Corbata</div>
            <div class="object" draggable="true" id="item32" data-weight="0.33">Manzana</div>
            <div class="object" draggable="true" id="item33" data-weight="0.11">Pincel</div>
            <div class="object" draggable="true" id="item34" data-weight="1.5">Plancha</div>
            <div class="object" draggable="true" id="item35" data-weight="0.75">Grapadora</div>
        </div>

        <div class="card">
            <div class="card-header text-center font-hartsfolk" style="margin-bottom: 5px; font-size:25px;">
                <h3>Zona de Arrastre</h3>
                <p class="text-left mb-2 position-absolute f-4">Arrastra aquí las palabras</p>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <div id="dropArea"
                    class="border-1-black" style="background-color: white; width: 65%; height: 150px; display: flex; 
                        justify-content: center; align-items: center; border-radius: 8px; position: relative;">

                    <div class="d-flex justify-content-center">
                        <div class="object" draggable="true" id="bloque1" style="margin-right: 5px;"></div>
                        <div class="object" draggable="true" id="bloque2" style="margin-right: 5px;"></div>
                        <div class="object" draggable="true" id="bloque3" style="margin-right: 5px;"></div>
                        <div class="object" draggable="true" id="bloque4" style="margin-right: 5px;"></div>
                        <div class="object" draggable="true" id="bloque5"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Mensaje de feedback -->
    <div class="message" id="message"></div>
    </div>

    <div id="pausa" class="modal" style="display: none;">
        <div class="modal_content">
            <h2>El juego está en pausa</h2>
            <p id="tiempo">Tiempo transcurrido: 00:00</p>
            <button onclick="volverAlJuego()">Volver al Juego</button>
            <button id="botonSalirJuego" onclick="salirDelJuego()">Salir</button>
        </div>
    </div>

    <script>
        let draggedItem = null; // Objeto que se está arrastrando
        let movimiento = 0;//contador
        let segundos = 0;
        let minutos = 0; // Minutos del cronómetro
        let cronometroInterval; // Intervalo del cronómetro
        let cronometroPausado = false; // Estado del cronómetro (pausado o no)
        const juego = "Juego1";
        iniciarCronometro();

        const Items = Array.from(document.querySelectorAll('.object')).filter(item => !item.id.startsWith('bloque'));

        function mostraritems() {
            // Ocultar todos los items inicialmente
            Items.forEach(item => item.style.display = 'none');

            // Seleccionar aleatoriamente 5 items de entre todos los disponibles
            const itemsAleatorios = Items.sort(() => 0.5 - Math.random()).slice(0, 5);

            // Ordenar los items seleccionados por su peso para mantener el orden correcto
            const itemRandom = itemsAleatorios.sort((a, b) => a.getAttribute('data-weight') - b.getAttribute('data-weight'));

            // Mostrar los items seleccionados en la parte superior y asignar sus posiciones correctas
            itemRandom.forEach((item, index) => {
                item.style.display = 'block';
                document.getElementById(`bloque${index + 1}`).setAttribute('data-correct-position', item.getAttribute('data-weight'));
                document.getElementById(`bloque${index + 1}`).innerHTML = "";
            });
        }

        mostraritems();


        // Habilitamos el arrastre y soltado en los objetos
        document.querySelectorAll('.object').forEach(item => {
            item.addEventListener('dragstart', function (e) {
                draggedItem = this; // Guardamos el objeto arrastrado
                setTimeout(() => this.style.visibility = 'hidden', 0); // Ocultar temporalmente el objeto
            });

            item.addEventListener('dragend', function (e) {
                setTimeout(() => this.style.visibility = 'visible', 0); // Volver a mostrar el objeto
                draggedItem = null; // Limpiamos la referencia del objeto arrastrado
            });

            const dropArea = document.getElementById('dropArea');

            item.addEventListener('dragover', function (e) {
                e.preventDefault(); // Permitir soltar el objeto aquí
            });

            item.addEventListener('drop', function (e) {
                e.preventDefault(); // Evitar comportamiento por defecto

                if (draggedItem !== this) {
                    //incremenatr el movimietno
                    movimiento++;
                    document.getElementById('contador').innerText = `Movimientos: ${movimiento}`;
                    // Intercambiamos los objetos arrastrados
                    const tempHTML = this.innerHTML;
                    const tempWeight = this.getAttribute('data-weight');
                    const tempId = this.id;

                    // Intercambiamos contenido y atributos
                    this.innerHTML = draggedItem.innerHTML;
                    this.setAttribute('data-weight', draggedItem.getAttribute('data-weight'));
                    this.id = draggedItem.id;

                    draggedItem.innerHTML = tempHTML;
                    draggedItem.setAttribute('data-weight', tempWeight);
                    draggedItem.id = tempId;
                }

                // Verificamos el orden automáticamente después de soltar
                ordenCorrecto();
            });
        });

        // Función para verificar si los objetos están en el orden correcto
        function ordenCorrecto() {
            const bloques = Array.from(document.getElementById('dropArea').querySelectorAll('.object')).filter(obj => obj.style.display !== 'none' && obj.hasAttribute('data-weight'));
            let correctos = 0;

            bloques.forEach((bloque, index) => {
                const correctWeight = bloque.getAttribute('data-correct-position');
                const currentWeight = bloque.getAttribute('data-weight');
                if (!bloque.id.startsWith(`item`)) {
                    bloque.style.backgroundColor = 'white'
                }
                else if (correctWeight && currentWeight && correctWeight === currentWeight) {
                    correctos++;
                    bloque.style.backgroundColor = 'lightgreen';
                } else {
                    bloque.style.backgroundColor = '#D53032';
                }
            });

            // Si todos están correctos
            if (correctos === 5) {

                const tiempoActual = `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
                limpiarCronometro();
                window.location.href = `ventanaGanadora.html?movimientos=${movimiento}&tiempo=${tiempoActual}&juego=${juego}`;
            }
        }
        //cargar cornometro 
        window.onload = function () {
            if (localStorage.getItem('minutos') && localStorage.getItem('segundos')) {
                minutos = parseInt(localStorage.getItem('minutos'), 10);
                segundos = parseInt(localStorage.getItem('segundos'), 10);
            }
            iniciarCronometro();
            document.getElementById('pausarBtn').addEventListener('click', pausarBoton);
        }

        //guardar el tiempo
        function guardarTiempoenlocalstorage() {
            //guardar el tiempo 
            localStorage.setItem('minutos', minutos);
            localStorage.setItem('segundos', segundos);
        }

        //cronometro
        function iniciarCronometro() {
            if (cronometroInterval !== null) {
                clearInterval(cronometroInterval);
            }
            cronometroInterval = setInterval(() => {
                if (!cronometroPausado) {
                    segundos++;
                    if (segundos == 60) {
                        minutos++;
                        segundos = 0;
                    }
                    document.getElementById('cronometro').innerText =
                        `Tiempo: ${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;

                    guardarTiempoenlocalstorage();

                }
            }, 1000)
        }

        iniciarCronometro();

        //detener cronometro 
        function pausarCronometro() {
            cronometroPausado = true;
        }

        //reanudar cronometro
        function reanudarCronmetro() {
            if (cronometroPausado == true) {
                cronometroPausado = false;
            }
        }

        //limpiar Cronometro 
        function limpiarCronometro() {
            clearInterval(cronometroInterval);
            cronometroInterval = null;
            segundos = 0;
            minutos = 0;
            document.getElementById("cronometro").innerText = 'Tiempo: 00:00';
            //limpiar local storage
            localStorage.removeItem('minutos');
            localStorage.removeItem('segundos');
        }

        //boton de pausa
        function pausarBoton() {
            pausarCronometro();
            // Mostrar el modal de pausa
            const modal = document.getElementById('pausa');
            modal.style.display = "flex";

            // Mostrar el tiempo transcurrido en el modal
            document.getElementById('tiempo').innerText =
                `Tiempo transcurrido: ${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
        }

        function volverAlJuego() {
            const modal = document.getElementById('pausa');
            modal.style.display = "none"
            reanudarCronmetro()

        }
        function salirDelJuego() {
            limpiarCronometro();
            window.location.href = "tableroJuegos.php"
        }


    </script>

</body>

</html>