<?php
require_once 'Conexion.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Validación mínima
if (!isset($data['id_servicio'], $data['personas'], $data['total_estimado'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

try {
    $db = (new Conexion())->conn;

    $stmt = $db->prepare("INSERT INTO cotizacion_evento (id_cliente, id_servicio, fecha, personas, total_estimado) VALUES (?, ?, NOW(), ?, ?)");

    $stmt->execute([
        $data['id_cliente'],
        $data['id_servicio'],
        $data['personas'],
        $data['total_estimado']
    ]);

    echo json_encode(['success' => true, 'message' => 'Cotización registrada correctamente']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en servidor', 'error' => $e->getMessage()]);
}
?>
