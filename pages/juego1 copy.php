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

<body class="background-image margin-0 d-flex flex-column" style="min-height: 100vh; padding: 75px 30px;">
    <div class="p-4 flex-grow-1" style="width: 100%;">
        <h1 class="text-center mb-2 font-hartsfolk">Ordena los objetos del más liviano al más pesado</h1>
        <div class="border border-light rounded mb-3 p-4 bg-light shadow-sm font-hartsfolk">
            <h2 class="d-flex justify-content-start  mb-4">Arrastra y suelta los objetos en el orden correcto.</h2>
            <div class="d-flex justify-content-start align-items-center" style="padding-left: 30px">
                <button id="pausarBtn" class="btn btn-outline-primary px-4 py-2"
                    style="margin-right: 150px">Pausar</button>
                <p id="contador" class="mb-0 text-muted" style="margin-right: 150px">Movimientos: 0</p>
                <p id="cronometro" class="mb-0 text-muted" style="margin-right: 150px">Tiempo: 00:00</p>
            </div>
        </div>

        <div class="game-area" id="gameArea1">

        </div>

        <div class="card">
            <div class="card-header text-center font-hartsfolk" style="margin-bottom: 5px; font-size:25px;">
                <h3>Zona de Arrastre</h3>
                <p class="text-left mb-2 position-absolute f-4">Arrastra aquí las palabras</p>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <div id="dropArea" class="border-1-black" style="background-color: white; width: 65%; height: 150px; display: flex; 
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
            const Items = itemsData;
            const itemsAleatorios = Items.sort(() => 0.5 - Math.random()).slice(0, 5);
            const itemRandom = itemsAleatorios.sort((a, b) => a.weight - b.weight);

            itemRandom.forEach((item, index) => {
                const itemElement = document.createElement('div');
                itemElement.classList.add('object');
                itemElement.setAttribute('draggable', 'true');
                itemElement.setAttribute('id', item.id);
                itemElement.setAttribute('data-weight', item.weight);
                itemElement.innerHTML = item.name;
                document.getElementById('gameArea1').appendChild(itemElement);
                document.getElementById(`bloque${index + 1}`).setAttribute('data-correct-position', item.weight);
                document.getElementById(`bloque${index + 1}`).innerHTML = "";
            });
        }

        // Cargar los datos desde palabra.json
        let itemsData = [];
        $.getJSON('../js/palabra.json', function (data) {
            itemsData = data;
            mostraritems();
        });

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
            // Eventos para dispositivos móviles
            item.addEventListener('touchstart', function (e) {
                draggedItem = this;
                setTimeout(() => this.style.visibility = 'hidden', 0);
                e.preventDefault();
            });

            item.addEventListener('touchend', function (e) {
                setTimeout(() => this.style.visibility = 'visible', 0);
                if (draggedItem !== null) {
                    // Incrementar el movimiento al soltar
                    movimiento++;
                    document.getElementById('contador').innerText = `Movimientos: ${movimiento}`;
                    // Encontrar el elemento debajo del toque final
                    const touchLocation = e.changedTouches[0];
                    const element = document.elementFromPoint(touchLocation.clientX, touchLocation.clientY);
                    if (element && element.classList.contains('object') && element !== draggedItem) {
                        // Intercambiamos los objetos arrastrados
                        const tempHTML = element.innerHTML;
                        const tempWeight = element.getAttribute('data-weight');
                        const tempId = element.id;

                        // Intercambiamos contenido y atributos
                        element.innerHTML = draggedItem.innerHTML;
                        element.setAttribute('data-weight', draggedItem.getAttribute('data-weight'));
                        element.id = draggedItem.id;

                        draggedItem.innerHTML = tempHTML;
                        draggedItem.setAttribute('data-weight', tempWeight);
                        draggedItem.id = tempId;
                    }
                }
                draggedItem = null;
                // Verificamos el orden automáticamente después de soltar
                ordenCorrecto();
            });

            item.addEventListener('touchmove', function (e) {
                e.preventDefault();
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
            localStorage.removeItem('minutos');
            localStorage.removeItem('segundos');
        }

        //boton de pausa
        function pausarBoton() {
            pausarCronometro();
            const modal = document.getElementById('pausa');
            modal.style.display = "flex";

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