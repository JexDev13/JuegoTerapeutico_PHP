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

if (!isset($_SESSION['pacienteId'])) {
    $response = ['status' => 'error', 'message' => 'Paciente no seleccionado.'];
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear') {
        // Crear un registro de estadistica
        $pacienteId = $_SESSION['pacienteId'];
        $juego = $_POST['juego'];
        $dificultad = $_POST['dificultad'];
        $tiempo = $_POST['tiempo'];
        $movimientos = $_POST['movimientos'] ?? '';
        $evaluacion = $_POST['evaluacion'] ?? '';

        try {
            $sql = "INSERT INTO estadisticas (`pacienteId`, `tiempoJuego`, `movimientos`, `evaluacion`, `tipoJuego`, `dificultad`) 
                    VALUES (:pacienteId, :tiempo, :movimientos, :evaluacion, :juego, :dificultad)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':pacienteId', $pacienteId);
            $stmt->bindParam(':tiempo', $tiempo);
            $stmt->bindParam(':movimientos', $movimientos);
            $stmt->bindParam(':evaluacion', $evaluacion);
            $stmt->bindParam(':juego', $juego);
            $stmt->bindParam(':dificultad', $dificultad);
            $stmt->execute();

            $response = ['status' => 'success', 'message' => 'Registrado exitosamente.'];
        } catch (PDOException $e) {
            $response = ['status' => 'error', 'message' => 'Error al registrar paciente: ' . $e->getMessage()];
        }
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($response);
}
?>