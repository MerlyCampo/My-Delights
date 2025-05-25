<?php
require_once 'Conexion.php';

class DetallePedido {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->conn;
    }

    public function insertar($id_pedido, $id_producto, $cantidad, $subtotal) {
        $query = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, subtotal)
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_pedido, $id_producto, $cantidad, $subtotal]);
    }

    public function obtenerPorPedido($id_pedido) {
        $query = "SELECT dp.*, p.nombre 
                  FROM Detalle_Pedido dp
                  JOIN Producto p ON dp.id_producto = p.id_producto
                  WHERE dp.id_pedido = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
