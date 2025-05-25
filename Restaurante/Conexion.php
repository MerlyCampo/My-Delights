<?php
class Conexion
{
    private $host = "localhost";     // o 127.0.0.1
    private $db = "my_delights";     // nombre de la base de datos
    private $user = "root";          // usuario de MySQL 
    private $pass = "";              // contraseña de MySQL
    private $charset = "utf8mb4";

    public $conn;

    public function __construct()
    {
        $this->conectar();
    }

    public function conectar()
    {
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
            $this->conn = new PDO($dsn, $this->user, $this->pass);

            // Configura errores para que se lancen excepciones
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function desconectar()
    {
        $this->conn = null;
    }
}
?>