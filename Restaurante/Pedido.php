<?php
require_once 'Conexion.php';

class Pedido
{
    private $db;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->db = $conexion->conn;
    }

    public function insertar($id_cliente, $tipo_pedido, $total, $descuento, $envio, $estado)
    {
        $query = "INSERT INTO Pedido (id_cliente, tipo_pedido, total, descuento_aplicado, costo_envio, estado)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_cliente, $tipo_pedido, $total, $descuento, $envio, $estado]);
    }

    public function obtenerUltimoPedido($id_cliente)
    {
        $query = "Select Max(id_pedido) id_pedido from pedido Where id_cliente = ? and estado = 'Pendiente'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorCliente($id_cliente)
    {
        $query = "SELECT * FROM Pedido WHERE id_cliente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar($id_pedido, $total, $descuento, $envio, $estado)
    {
        $query = "UPDATE pedido SET total = ?, descuento_aplicado = ?, costo_envio = ?,estado = ? WHERE id_pedido = ?";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([$total, $descuento, $envio, $estado, $id_pedido]);
    }
}
?>