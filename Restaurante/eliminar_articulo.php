<?php
require 'conexion.php';
$db = new Conexion();
$pdo = $db->conn;

$id = $_GET['id'] ?? null;

if ($id) {
   $sql = "DELETE FROM blog_articulos WHERE id = ?";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([(int) $id]);
}

header("Location: admin_articulos.php");
exit;
