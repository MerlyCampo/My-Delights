<?php
require 'conexion.php';
$db = new Conexion();
$pdo = $db->conn;

$id = $_GET['id'] ?? null;
if (!$id) {
   echo "Artículo no encontrado.";
   exit;
}

$mensaje = "";

// Obtener datos actuales
$sql = "SELECT * FROM blog_articulos WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([(int) $id]);
$articulo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$articulo) {
   echo "Artículo no encontrado.";
   exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $titulo = $_POST['titulo'] ?? '';
   $introduccion = $_POST['introduccion'] ?? '';
   $contenido = $_POST['contenido'] ?? '';
   $imagen = $_POST['imagen'] ?? '';

   if ($titulo && $introduccion && $contenido && $imagen) {
      $sql = "UPDATE blog_articulos SET titulo = ?, introduccion = ?, contenido = ?, imagen = ? WHERE id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$titulo, $introduccion, $contenido, $imagen, (int) $id]);

      $mensaje = "Artículo actualizado correctamente.";

      // Actualizamos los datos para que se reflejen en el formulario
      $articulo['titulo'] = $titulo;
      $articulo['introduccion'] = $introduccion;
      $articulo['contenido'] = $contenido;
      $articulo['imagen'] = $imagen;
   } else {
      $mensaje = "Por favor, completa todos los campos.";
   }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <title>Editar Artículo | Blog</title>
   <link rel="stylesheet" href="stylesBlog.css">
</head>

<body>
   <header>
      <h1>Editar Artículo</h1>
   </header>
   <main class="form-container">
      <?php if ($mensaje): ?>
         <p><strong><?= htmlspecialchars($mensaje) ?></strong></p>
      <?php endif; ?>
      <form method="POST">
         <label for="titulo">Título:</label><br>
         <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($articulo['titulo']) ?>"
            required><br><br>

         <label for="introduccion">Introducción:</label><br>
         <textarea name="introduccion" id="introduccion" rows="4"
            required><?= htmlspecialchars($articulo['introduccion']) ?></textarea><br><br>

         <label for="contenido">Contenido (HTML permitido):</label><br>
         <textarea name="contenido" id="contenido" rows="8"
            required><?= htmlspecialchars($articulo['contenido']) ?></textarea><br><br>

         <label for="imagen">URL de la imagen o ruta:</label><br>
         <input type="text" name="imagen" id="imagen" value="<?= htmlspecialchars($articulo['imagen']) ?>"
            required><br><br>

         <button type="submit">Guardar cambios</button>
      </form>
      <br>
      <a href="admin_articulos.php">← Volver a administración</a>
   </main>
</body>

</html>