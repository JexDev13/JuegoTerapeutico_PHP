<?php

require __DIR__ . '/../config.php';

session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: inicioSesion.php");
    exit();
}

function calcularEdad($fechaNacimiento)
{
    $fecha = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fecha);
    return $edad->y;
}

$cedula = $nombre = $apellido = $fechaNacimiento = "";
$buttonText = "Registrar paciente";
$formMethod = "post";
$formAction = "/../tase/controllers/paciente_crud.php";

unset($_SESSION['cedula']);
unset($_SESSION['nombre_completo']);
unset($_SESSION['edad']);

// Leer los pacientes desde la base de datos
$sql = "SELECT id, cedula, CONCAT(nombre, ' ', apellido) AS nombre_completo, fechaNacimiento 
        FROM paciente 
        WHERE terapeutaId = :terapeutaId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':terapeutaId', $_SESSION['idUsuario'], PDO::PARAM_INT);
$stmt->execute();
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<?php include __DIR__ . '/../components/menu.php'; ?>

<body class="background-image margin-0">
    <div class="p-4" style="min-height: 85vh; width: 100%;">
        <div class="d-flex align-items-center mb-3">
            <!-- Nuevo paciente -->
            <button id="new_patient_btn"
                class="boton back-color-cuaternary color-tertiary d-flex px-4 py-2 rounded align-items-center justify-content-center w-25">
                <span class="material-icons">add</span>
                <span class="ms-2">Registrar nuevo paciente</span>
            </button>
        </div>

        <!-- Formulario para agregar paciente -->
        <form id="patient_form" method="<?php echo $formMethod; ?>" action="<?php echo $formAction; ?>"
            class="border-1-black p-4 rounded bg-white w-25" style="display: none;">
            <input type="hidden" name="accion" id="accion" value="crear">

            <div class="mb-3">
                <input type="text" class="form-control" id="cedula" name="cedula" required placeholder="Cédula">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Nombre">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="apellido" name="apellido" required placeholder="Apellido">
            </div>
            <div class="mb-3">
                <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required
                    placeholder="Fecha de Nacimiento" onchange="validateAge()">
                <span id="ageError" style="color: red; display: none; font-size: 12px;">
                    *El paciente debe tener al menos 40 años.</span>
            </div>
            <input type="submit" class="btn btn-success w-100" value="<?php echo $buttonText; ?>">
        </form>

        <!-- Fila con el título y el buscador -->
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-left mb-4">Lista de Pacientes</h1>
            <!-- Buscador -->
            <div class="d-flex rounded overflow-hidden gap-2 w-30">
                <select id="search_type" class="form-select border-0">
                    <option value="none">Ninguno</option>
                    <option value="cedula">Cédula Paciente</option>
                    <option value="nombre">Nombre Paciente</option>
                </select>
                <input type="text" id="search_input" class="form-control border-0" placeholder="Buscar paciente...">
                <button id="search_btn"
                    class="boton back-color-cuaternary color-tertiary border-0 d-flex align-items-center justify-content-center">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </div>


        <!-- Tabla de pacientes -->
        <div>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Edad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($pacientes)) {
                        foreach ($pacientes as $paciente) {
                            echo "<tr>";
                            echo "<td><a href='../pages/tableroJuegos.php?cedula=" . $paciente["cedula"] . "&nombre_completo=" . urlencode($paciente["nombre_completo"]) . "&edad=" . calcularEdad($paciente['fechaNacimiento']) . "'>" . $paciente["cedula"] . "</a></td>";
                            echo "<td>" . $paciente["nombre_completo"] . "</td>";
                            echo "<td>" . calcularEdad($paciente['fechaNacimiento']) . "</td>";
                            echo "<td class='text-center'>
                                    <div class='d-flex gap-2 justify-content-center'>
                                        <button class='btn btn-warning btn-sm update_patient_btn d-inline-flex align-items-center gap-1' data-cedula='" . $paciente["cedula"] . "'>
                                            <span class='material-icons'>edit</span> <span>Actualizar</span>
                                        </button>
                                        <button class='btn btn-danger btn-sm delete_patient_btn d-inline-flex align-items-center gap-1' data-cedula='" . $paciente["cedula"] . "'>
                                            <span class='material-icons'>delete</span> <span>Eliminar</span>
                                        </button>
                                    </div>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>0 resultados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>



<script>
    function validateAge() {
        const inputDate = document.getElementById('fechaNacimiento');
        const ageError = document.getElementById('ageError');
        const birthDate = new Date(inputDate.value);
        const currentDate = new Date();

        // Calcular la edad
        let age = currentDate.getFullYear() - birthDate.getFullYear();
        const month = currentDate.getMonth() - birthDate.getMonth();

        if (month < 0 || (month === 0 && currentDate.getDate() < birthDate.getDate())) {
            age--;
        }

        // Validar que la edad sea al menos 40
        if (age < 40) {
            ageError.style.display = 'inline';
            inputDate.value = '';
        } else {
            ageError.style.display = 'none';
        }
    }

    function calcularEdad(fechaNacimiento) {
        const fecha = new Date(fechaNacimiento);
        const hoy = new Date();
        let edad = hoy.getFullYear() - fecha.getFullYear();
        const mes = hoy.getMonth() - fecha.getMonth();

        if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) {
            edad--;
        }

        return edad;
    }

    document.getElementById('search_type').addEventListener('change', function () {
        const searchType = this.value;
        const searchInput = document.getElementById('search_input');

        if (searchType === 'none') {
            searchInput.value = '';
        }
    });


    $(document).ready(function () {
        // Mostrar el formulario para "Nuevo paciente"
        $('#new_patient_btn').click(function () {
            $('#patient_form').slideToggle();
            $('#patient_form').attr('action', '/tase/controllers/paciente_crud.php');
            $('#patient_form input[type="submit"]').val('Registrar Paciente');
            $('#patient_form input[type="text"], #patient_form input[type="date"]').val('');
        });

        // Mostrar el formulario con datos de paciente para "Actualizar"
        $(document).on('click', '.update_patient_btn', function () {
            var cedula = $(this).data('cedula'); // Obtener cédula del paciente a actualizar
            $.ajax({
                url: '/tase/controllers/paciente_crud.php',
                type: 'POST',
                data: { cedula: cedula, accion: 'actualizar' },
                dataType: 'json',
                success: function (data) {
                    // Si la respuesta contiene error
                    if (data.error) {
                        alert(data.error); // Si no se encuentra el paciente, mostrar error
                    } else {
                        // Si se encuentran los datos del paciente, llenar el formulario
                        $('#cedula').val(data.cedula).prop('readonly', true);
                        $('#nombre').val(data.nombre);
                        $('#apellido').val(data.apellido);
                        $('#fechaNacimiento').val(data.fechaNacimiento);
                        $('#patient_form').slideDown();
                        $('#patient_form input[type="submit"]').val('Actualizar Paciente');
                        $('#accion').val('actualizar');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Error:', textStatus, errorThrown);
                    alert('Ocurrió un error al intentar obtener los datos del paciente.');
                }
            });
        });

        // Enviar el formulario de actualización
        $('#patient_form').submit(function (e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function (response) {
                    console.log(response);
                    // Verifica si la respuesta es exitosa
                    if (response.status === 'success') {
                        alert(response.message);
                        location.reload();
                    } else {
                        console.log('Error: ' + response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Error:', textStatus, errorThrown);
                    alert('Ocurrió un error al actualizar el paciente.');
                }
            });
        });

        // Eliminar un paciente
        $(document).on('click', '.delete_patient_btn', function () {
            var cedula = $(this).data('cedula');
            var confirmDelete = confirm('¿Estás seguro de que deseas eliminar este paciente?');

            if (confirmDelete) {
                $.ajax({
                    url: '/tase/controllers/paciente_crud.php',
                    type: 'POST',
                    data: { cedula: cedula, accion: 'eliminar' },
                    success: function (response) {
                        console.log("Respuesta del servidor:", response);
                        // Verifica si la respuesta es exitosa
                        if (response.status === 'success') {
                            alert(response.message); // Mostrar el mensaje de éxito
                            location.reload(); // Recargar la página para actualizar la lista de pacientes
                        } else {
                            alert(response.message); // Mostrar el mensaje de error si no se pudo eliminar
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error:', textStatus, errorThrown);
                    }
                });
            } else {
                alert('Eliminación cancelada');
            }
        });

        // Enviar solicitud de búsqueda
        $('#search_btn').click(function () {
            var searchType = $('#search_type').val();
            var searchText = $('#search_input').val();

            $.ajax({
                url: '/tase/controllers/paciente_crud.php',
                type: 'POST',
                data: {
                    accion: 'buscar',
                    tipo_busqueda: searchType,
                    texto_busqueda: searchText
                },
                success: function (response) {
                    $('table tbody').empty();
                    if (response.status === 'success') {
                        if (response.pacientes.length > 0) {
                            response.pacientes.forEach(function (paciente) {
                                var row = "<tr>";
                                row += "<td><a href='../pages/tableroJuegos.php?cedula=" + paciente.cedula + "&nombre_completo=" + encodeURIComponent(paciente.nombre_completo) + "&edad=" + calcularEdad(paciente.fechaNacimiento) + "'>" + paciente.cedula + "</a></td>";
                                row += "<td>" + paciente.nombre_completo + "</td>";
                                row += "<td>" + calcularEdad(paciente.fechaNacimiento) + "</td>";
                                row += "<td class='text-center'>" +
                                    "<div class='d-flex gap-2 justify-content-center'>" +
                                    "<button class='btn btn-warning btn-sm update_patient_btn d-inline-flex align-items-center gap-1' data-cedula='" + paciente.cedula + "'>" +
                                    "<span class='material-icons'>edit</span> <span>Actualizar</span>" +
                                    "</button>" +
                                    "<button class='btn btn-danger btn-sm delete_patient_btn d-inline-flex align-items-center gap-1' data-cedula='" + paciente.cedula + "'>" +
                                    "<span class='material-icons'>delete</span> <span>Eliminar</span>" +
                                    "</button>" +
                                    "</div>" +
                                    "</td>";
                                row += "</tr>";
                                $('table tbody').append(row);
                            });
                        }
                    } else {
                        $('table tbody').append("<tr><td colspan='4' class='text-center'>No se encontraron resultados</td></tr>");
                    }
                }
            });
        });
    });
</script>

</html>