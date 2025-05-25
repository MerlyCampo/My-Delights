<?php
require_once 'Conexion.php';

class ServicioEvento
{
   private $db;

   public function __construct()
   {
      $conexion = new Conexion();
      $this->db = $conexion->conn;
   }

   // Obtener todos los servicios/eventos
   public function obtenerTodos()
   {
      $query = "SELECT * FROM Servicio_Evento";
      $stmt = $this->db->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   // Obtener un servicio por ID
   public function obtenerPorId($id_servicio)
   {
      $query = "SELECT * FROM Servicio_Evento WHERE id_servicio = ?";
      $stmt = $this->db->prepare($query);
      $stmt->execute([$id_servicio]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
   }

   // Insertar nuevo servicio/evento
   public function insertar($tipo_evento, $descripcion, $precio_base, $precio_por_persona, $foto_url)
   {
      $query = "INSERT INTO Servicio_Evento (tipo_evento, descripcion, precio_base, precio_por_persona, foto_url)
                  VALUES (?, ?, ?, ?, ?)";
      $stmt = $this->db->prepare($query);
      return $stmt->execute([$tipo_evento, $descripcion, $precio_base, $precio_por_persona, $foto_url]);
   }
}
?>