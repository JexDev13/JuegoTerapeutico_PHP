<?php
header('Content-Type: application/json');
require __DIR__ . '/../config.php';

$response = array('status' => 'error', 'message' => 'Operación fallida');

session_start();

if (!isset($_SESSION['idUsuario'])) {
    $response = ['status' => 'error', 'message' => 'Usuario no autenticado.'];
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $accion = $_POST['accion'] ?? '';
    $cedula = $_POST['cedula'] ?? '';
    $nombre = $_POST['nombre'] ?? '';

    if ($accion === 'crear') {
        // Crear un paciente
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';
        $terapeutaId = $_SESSION['idUsuario'];

        try {
            $sql = "INSERT INTO paciente (cedula, nombre, apellido, fechaNacimiento, terapeutaId) 
                    VALUES (:cedula, :nombre, :apellido, :fechaNacimiento, :terapeutaId)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':cedula', $cedula);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
            $stmt->bindParam(':terapeutaId', $terapeutaId);
            $stmt->execute();

            $response = ['status' => 'success', 'message' => 'Paciente registrado exitosamente.'];
        } catch (PDOException $e) {
            $response = ['status' => 'error', 'message' => 'Error al registrar paciente: ' . $e->getMessage()];
        }
    } elseif ($accion === 'actualizar') {
        // Actualizar paciente
        if (empty($cedula)) {
            echo json_encode(['error' => 'Cédula no proporcionada']);
            exit();
        }

        // Verificar si es solo para obtener los datos del paciente (lectura)
        if (empty($_POST['nombre']) && empty($_POST['apellido']) && empty($_POST['fechaNacimiento'])) {
            $sql = "SELECT cedula, nombre, apellido, fechaNacimiento FROM paciente WHERE cedula = :cedula";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();

            $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($paciente) {
                echo json_encode($paciente);
            } else {
                echo json_encode(['error' => 'Paciente no encontrado']);
            }
            exit();
        } else {
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';

            try {
                $sql = "UPDATE paciente SET nombre = :nombre, apellido = :apellido, fechaNacimiento = :fechaNacimiento WHERE cedula = :cedula";
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':cedula', $cedula);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':apellido', $apellido);
                $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
                $stmt->execute();

                $response = [
                    'status' => 'success',
                    'message' => 'Datos del paciente actualizados exitosamente.'
                ];
            } catch (PDOException $e) {
                $response = ['status' => 'error', 'message' => 'Error al actualizar paciente: ' . $e->getMessage()];
            }
        }
    } elseif ($accion === 'eliminar') {
        try {
            // Eliminar el paciente
            $sql = "DELETE FROM paciente WHERE cedula = :cedula";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $response = ['status' => 'success', 'message' => 'Paciente eliminado exitosamente.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Paciente no encontrado'];
            }
        } catch (PDOException $e) {
            $response = ['status' => 'error', 'message' => 'Error al eliminar paciente: ' . $e->getMessage()];
        }
    } elseif ($accion === 'buscar') {
        $tipoBusqueda = $_POST['tipo_busqueda'] ?? '';
        $textoBusqueda = $_POST['texto_busqueda'] ?? '';

        // Buscar pacientes según el tipo de búsqueda
        if ($tipoBusqueda === 'cedula') {
            $sql = "SELECT id, cedula, CONCAT(nombre, ' ', apellido) AS nombre_completo, fechaNacimiento 
                    FROM paciente 
                    WHERE terapeutaId = :terapeutaId AND cedula LIKE :textoBusqueda";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':textoBusqueda', $textoBusqueda);
        } elseif ($tipoBusqueda === 'nombre') {
            $sql = "SELECT id, cedula, CONCAT(nombre, ' ', apellido) AS nombre_completo, fechaNacimiento 
                    FROM paciente 
                    WHERE terapeutaId = :terapeutaId AND nombre LIKE :textoBusqueda";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':textoBusqueda', $textoBusqueda);
        } elseif ($tipoBusqueda === 'none') {
            $sql = "SELECT id, cedula, CONCAT(nombre, ' ', apellido) AS nombre_completo, fechaNacimiento 
                    FROM paciente 
                    WHERE terapeutaId = :terapeutaId";
            $stmt = $pdo->prepare($sql);
        }

        $stmt->bindParam(':terapeutaId', $_SESSION['idUsuario'], PDO::PARAM_INT);
        $stmt->execute();
        $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($pacientes) {
            echo json_encode(['status' => 'success', 'pacientes' => $pacientes]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron resultados']);
        }
        exit();
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($response);
}
?>