<?php
require 'conexion.php';

$db = new Conexion();
$pdo = $db->conn;

$sql = "SELECT id, titulo, introduccion, imagen FROM blog_articulos ORDER BY fecha_publicacion DESC";
$stmt = $pdo->query($sql);
$articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Blog | My Delights</title>
    <link rel="stylesheet" href="stylesBlog.css" />
    <style>
        /* Post destacado arriba */
        .post-destacado {
            max-width: 700px;
            margin: 40px auto 60px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: left;
        }

        .post-destacado img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
        }

        .post-destacado .contenido {
            padding: 20px;
        }

        .post-destacado h2 {
            color: #D2691E;
            margin-bottom: 15px;
        }

        .post-destacado p {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .post-destacado a.leer-mas {
            color: #D2691E;
            font-weight: bold;
            text-decoration: none;
        }

        .post-destacado a.leer-mas:hover {
            text-decoration: underline;
        }

        /* Panel doble debajo */
        .panel-doble {
            display: flex;
            justify-content: center;
            gap: 30px;
            max-width: 900px;
            margin: 0 auto 60px;
            flex-wrap: wrap;
        }

        .panel-doble article {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 440px;
            width: 100%;
            overflow: hidden;
            text-align: left;
            transition: transform 0.2s;
        }

        .panel-doble article:hover {
            transform: scale(1.02);
        }

        .panel-doble img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .panel-doble .contenido {
            padding: 15px 20px;
        }

        .panel-doble h2 {
            color: #D2691E;
            margin-bottom: 12px;
            font-size: 20px;
        }

        .panel-doble p {
            font-size: 15px;
            margin-bottom: 10px;
        }

        .panel-doble a.leer-mas {
            color: #D2691E;
            font-weight: bold;
            text-decoration: none;
        }

        .panel-doble a.leer-mas:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <h1>5 ideas de cenas gourmet y más</h1>
    </header>

    <main>
        <?php
        $titulos_panel = [
            'Postres rápidos con solo 3 ingredientes',
            'Cómo maridar vinos y quesos como un experto'
        ];

        $post_destacado = null;
        $posts_panel = [];
        $posts_otros = [];

        foreach ($articulos as $art) {
            if ($art['titulo'] === '5 ideas de cenas gourmet') {
                $post_destacado = $art;
            } elseif (in_array($art['titulo'], $titulos_panel)) {
                $posts_panel[] = $art;
            } else {
                $posts_otros[] = $art;
            }
        }
        ?>

        <?php if ($post_destacado): ?>
            <article class="post-destacado">
                <a href="blog_detalle.php?id=<?= (int) $post_destacado['id'] ?>">
                    <img src="<?= htmlspecialchars($post_destacado['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                        alt="<?= htmlspecialchars($post_destacado['titulo'], ENT_QUOTES, 'UTF-8') ?>" />
                </a>
                <div class="contenido">
                    <h2><?= htmlspecialchars($post_destacado['titulo'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <p><?= htmlspecialchars($post_destacado['introduccion'], ENT_QUOTES, 'UTF-8') ?></p>
                    <a href="blog_detalle.php?id=<?= (int) $post_destacado['id'] ?>" class="leer-mas">Leer más →</a>
                </div>
            </article>
        <?php endif; ?>

        <?php if (count($posts_panel) > 0): ?>
            <div class="panel-doble">
                <?php foreach ($posts_panel as $post): ?>
                    <article>
                        <a href="blog_detalle.php?id=<?= (int) $post['id'] ?>">
                            <img src="<?= htmlspecialchars($post['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8') ?>" />
                        </a>
                        <div class="contenido">
                            <h2><?= htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8') ?></h2>
                            <p><?= htmlspecialchars($post['introduccion'], ENT_QUOTES, 'UTF-8') ?></p>
                            <a href="blog_detalle.php?id=<?= (int) $post['id'] ?>" class="leer-mas">Leer más →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
                <!-- Aquí va la sección para otros posts -->
                <?php if (count($posts_otros) > 0): ?>
                    <section class="blog-container">
                        <?php foreach ($posts_otros as $post): ?>
                            <article class="post-card">
                                <a href="blog_detalle.php?id=<?= (int) $post['id'] ?>">
                                    <img src="<?= htmlspecialchars($post['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                                        alt="<?= htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8') ?>" />
                                </a>
                                <div class="post-content">
                                    <h2><?= htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8') ?></h2>
                                    <p><?= htmlspecialchars($post['introduccion'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <a href="blog_detalle.php?id=<?= (int) $post['id'] ?>" class="leer-mas">Leer más →</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div style="text-align: center; margin-bottom: 40px;">
            <a href="Restaurante.php" class="btn-volver-inicio">← Volver al inicio</a>
        </div>
        <div style="text-align: center; margin-bottom: 40px;">
            <a href="nuevo_articulo.php" class="btn-volver-inicio">+ Crear nuevo artículo</a>
        </div>
    </main>
    <a href="admin_articulos.php">Administrar artículos</a>
</body>

</html>