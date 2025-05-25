<?php
require_once 'Conexion.php';

class Cliente
{
    private $db;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->db = $conexion->conn;
    }

    public function login($user, $password)
    {
        $query = "SELECT * FROM usuarios Where usuario = ? and password = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function consultaPedidos($cliente)
    {
        $query = "SELECT p.id_pedido, concat(c.nombre, ' ', c.apellido) nombres, c.telefono, p.total, p.descuento_aplicado, p.tipo_pedido From cliente c inner join pedido p on c.id_cliente = p.id_cliente where c.id_cliente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar($cedula, $nombre, $apellido, $sexo, $fecha, $direccion, $telefono, $email, $tipo, $credito)
    {
        $query = "INSERT INTO Cliente (cedula, nombre, apellido, sexo, fecha_nacimiento, direccion, telefono, email, tipo_cliente, tiene_credito)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$cedula, $nombre, $apellido, $sexo, $fecha, $direccion, $telefono, $email, $tipo, $credito]);
    }

    public function obtenerTodos()
    {
        $query = "SELECT * FROM Cliente";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarClienteId($id)
    {
        $query = "Select * From Cliente Where id_cliente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($clienteId, $nombre, $apellido, $email, $cedula, $sexo, $fechaNacimiento, $direccion, $telefono, $tipo_cliente, $tiene_credito)
    {
        $query = "UPDATE Cliente SET cedula=?, nombre=?, apellido=?, sexo=?, fecha_nacimiento=?, direccion=?, telefono=?, email=?, tipo_cliente=?, tiene_credito=? WHERE id_cliente=?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$cedula, $nombre, $apellido, $sexo, $fechaNacimiento, $direccion, $telefono, $email, $tipo_cliente, $tiene_credito, $clienteId,]);
    }

    public function eliminar($id)
    {
        $query = "DELETE FROM Cliente WHERE id_cliente=?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>