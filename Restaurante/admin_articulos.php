<?php
require 'conexion.php';
$db = new Conexion();
$pdo = $db->conn;

$sql = "SELECT id, titulo FROM blog_articulos ORDER BY id DESC";
$stmt = $pdo->query($sql);
$articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8" />
   <title>Administrar Artículos</title>
   <link rel="stylesheet" href="stylesBlog.css" />
</head>

<body>
   <header>
      <h1>Administrar Artículos</h1>
   </header>
   <main>
      <a href="nuevo_articulo.php">+ Nuevo artículo</a>
      <table border="1" cellpadding="8" cellspacing="0" style="margin-top:20px;">
         <thead>
            <tr>
               <th>ID</th>
               <th>Título</th>
               <th>Acciones</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($articulos as $art): ?>
               <tr>
                  <td><?= htmlspecialchars($art['id']) ?></td>
                  <td><?= htmlspecialchars($art['titulo']) ?></td>
                  <td>
                     <a href="editar_articulo.php?id=<?= (int) $art['id'] ?>">Editar</a> |
                     <a href="eliminar_articulo.php?id=<?= (int) $art['id'] ?>"
                        onclick="return confirm('¿Seguro que quieres eliminar este artículo?');">Eliminar</a>
                  </td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
      <br>
      <a href="blog1.php">← Volver al Blog</a>
   </main>
</body>

</html>