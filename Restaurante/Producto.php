<?php
require_once 'Conexion.php';

class Producto
{
    private $db;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->db = $conexion->conn;
    }

    // Obtener todos los productos
    public function obtenerTodos()
    {
        $query = "SELECT * FROM Producto";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Obtener producto con ingredientes
    public function obtenerConIngredientes()
    {
        $query = "
            SELECT p.id_producto, p.nombre AS producto_nombre, p.descripcion, p.precio, p.foto_url, p.tipo_producto,
                   GROUP_CONCAT(i.nombre SEPARATOR ', ') AS ingredientes
            FROM Producto p
            LEFT JOIN Producto_Ingrediente pi ON p.id_producto = pi.id_producto
            LEFT JOIN Ingrediente i ON pi.id_ingrediente = i.id_ingrediente
            GROUP BY p.id_producto
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener producto por ID
    public function obtenerPorId($id)
    {
        $query = "SELECT * FROM Producto WHERE id_producto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insertar nuevo producto
    public function insertar($nombre, $descripcion, $tipo, $precio, $fotoUrl)
    {
        $query = "INSERT INTO Producto (nombre, descripcion, tipo_producto, precio, foto_url)
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nombre, $descripcion, $tipo, $precio, $fotoUrl]);
    }
}
?>