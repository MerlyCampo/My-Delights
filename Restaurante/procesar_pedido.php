<?php
require_once 'Pedido.php';
require_once 'Cliente.php';
require_once 'DetallePedido.php';
$pedido = new Pedido();
$cliente = new Cliente();
$detalle = new DetallePedido();

$data = json_decode(file_get_contents('php://input'), true);

// Validar si hay productos
if (!isset($data['productos']) || count($data['productos']) === 0 || !isset($data['clienteId'])) {
   echo json_encode(['success' => false, 'message' => 'No se recibió ningún producto o cliente']);
   exit;
}

try {

   $dataCliente = $cliente->buscarClienteId($data['clienteId']);

   if (!$dataCliente) {
      echo json_encode(['success' => false, 'message' => 'Error consultando cliente']);
      exit;
   } else {
      $pedido->insertar($data['clienteId'], $data['tipoPedido'], 0, 0, 0, 'Pendiente');
      $idPedido = $pedido->obtenerUltimoPedido($data['clienteId']);


      $total = 0;

      foreach ($data['productos'] as $producto) {
         $subtotal = $producto['precio'] * $producto['cantidad'];
         $total += $subtotal;

         $detalle->insertar(
            ($idPedido[0])['id_pedido'],
            $producto['id'],
            $producto['cantidad'],
            $subtotal
         );
      }

      $descuentoValor = 0;
      $envio = 0;
      switch ($dataCliente['tipo_cliente']) {
         case 'Nuevo':
            if ($total >= 250000) {
               $descuentoValor = ($total * 2 / 100);
            }
            break;
         case 'Casual':
            if ($total >= 250000) {
               $descuentoValor = ($total * 6 / 100);
            } else {
               $descuentoValor = ($total * 2 / 100);
            }
            break;
         case 'Permanente':
            ($total >= 250000) ? $descuentoValor = ($total * 10 / 100) : $descuentoValor = ($total * 4 / 100);
            break;
         default:
            $descuentoValor = 0;
            break;
      }

      $total = $total - $descuentoValor;
      $envio = ($total * (2 / 100));

      $pedido->actualizar(($idPedido[0])['id_pedido'], $total, $descuentoValor, $envio, 'En preparación');

      echo json_encode(['success' => true, 'message' => 'Pedido creado correctamente']);

      exit;
   }
} catch (Exception $e) {
   echo "Error: " . $e->getMessage();
}

?>