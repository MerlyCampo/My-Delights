<?php
require 'conexion.php';

$db = new Conexion();
$pdo = $db->conn;

$id = $_GET['id'] ?? null;

if (!$id) {
   echo "Artículo no encontrado.";
   exit;
}

$sql = "SELECT * FROM blog_articulos WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([(int) $id]);  // Aseguramos que el id sea entero
$articulo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$articulo) {
   echo "Artículo no encontrado.";
   exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <title><?= htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8') ?> | Blog</title>
   <link rel="stylesheet" href="stylesBlog.css">
</head>

<body>
   <header>
      <h1><?= htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8') ?></h1>
   </header>

   <main class="detalle-articulo">
      <img
         src="<?= !empty($articulo['imagen']) ? htmlspecialchars($articulo['imagen'], ENT_QUOTES, 'UTF-8') : 'placeholder.jpg' ?>"
         alt="Imagen del artículo <?= htmlspecialchars($articulo['titulo'], ENT_QUOTES, 'UTF-8') ?>">

      <p><?= htmlspecialchars($articulo['introduccion'], ENT_QUOTES, 'UTF-8') ?></p>
      <div class="contenido-articulo">
         <?= $articulo['contenido'] ?>
      </div>
      <div class="back-link">
         <a href="blog1.php">← Volver al blog</a>
      </div>
   </main>
</body>

</html>