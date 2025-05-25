<?php
require 'conexion.php';

$db = new Conexion();
$pdo = $db->conn;

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $titulo = $_POST['titulo'] ?? '';
   $introduccion = $_POST['introduccion'] ?? '';
   $contenido = $_POST['contenido'] ?? '';
   $imagen = $_POST['imagen'] ?? ''; // URL o ruta relativa de la imagen

   if ($titulo && $introduccion && $contenido && $imagen) {
      $sql = "INSERT INTO blog_articulos (titulo, introduccion, contenido, imagen, fecha_publicacion) VALUES (?, ?, ?, ?, NOW())";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$titulo, $introduccion, $contenido, $imagen]);

      $mensaje = "Artículo creado correctamente.";
   } else {
      $mensaje = "Por favor, completa todos los campos.";
   }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <title>Nuevo Artículo | Blog</title>
   <link rel="stylesheet" href="stylesBlog.css">
</head>

<body>
   <header>
      <h1>Nuevo Artículo</h1>
   </header>

   <main class="form-container">
      <?php if ($mensaje): ?>
         <p><strong><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></strong></p>
      <?php endif; ?>

      <form method="POST">
         <label for="titulo">Título:</label><br>
         <input type="text" name="titulo" id="titulo" required><br><br>

         <label for="introduccion">Introducción:</label><br>
         <textarea name="introduccion" id="introduccion" rows="4" required></textarea><br><br>

         <label for="contenido">Contenido (HTML permitido):</label><br>
         <textarea name="contenido" id="contenido" rows="8" required></textarea><br><br>

         <label for="imagen">URL de la imagen o ruta:</label><br>
         <input type="text" name="imagen" id="imagen" required><br><br>

         <button type="submit">Guardar artículo</button>
      </form>
      <br>
      <a href="blog1.php">← Volver al blog</a>
   </main>
</body>

</html>