<?php
require_once 'Producto.php';
require_once 'Utilities.php';

$productoModel = new Producto();
$productos = $productoModel->obtenerConIngredientes();

// Agrupar productos por tipo_producto
$agrupados = [];
foreach ($productos as $producto) {
    $tipo = $producto['tipo_producto'];
    $agrupados[$tipo][] = $producto;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú - My Delights</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        .modal-backdrop.show {
            opacity: 0.5;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: #f4f4f4;
        }

        header {
            background: #000000;
            color: white;
            text-align: center;
            padding: 1rem;
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            background: #333;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .menu-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .menu-item img {
            width: 120px;
            height: auto;
            border-radius: 10px;
            margin-right: 20px;
        }

        .menu-item h3 {
            margin: 0;
        }

        .menu-item p {
            margin: 5px 0;
        }

        .back {
            display: block;
            text-align: center;
            margin: 20px;
            color: #000000;
            text-decoration: none;
            font-size: 18px;
        }

        .quantity {
            display: flex;
            border: 2px solid #3498db;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 120px;
        }

        .quantity button {
            background-color: #3498db;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 20px;
            width: 30px;
            height: auto;
            text-align: center;
            transition: background-color 0.2s;
        }

        .quantity button:hover {
            background-color: #2980b9;
        }

        .input-box {
            width: 40px;
            text-align: center;
            border: none;
            padding: 8px 10px;
            font-size: 16px;
            outline: none;
        }

        /* Hide the number input spin buttons */
        .input-box::-webkit-inner-spin-button,
        .input-box::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .input-box[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function realizarPedido() {
            const inputs = document.querySelectorAll('.input-box');
            const pedido = [];

            inputs.forEach(input => {
                const cantidad = parseInt(input.value);
                if (cantidad > 0) {
                    pedido.push({
                        id: input.dataset.id,
                        nombre: input.dataset.nombre,
                        precio: parseFloat(input.dataset.precio),
                        cantidad: cantidad
                    });
                }
            });

            if (pedido.length === 0) {
                alert('No has seleccionado ningún producto.');
                return;
            }

            console.log('Pedido generado:', pedido);
            return pedido;
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Selecciona todos los botones plus y minus
            const plusButtons = document.querySelectorAll('.plus');
            const minusButtons = document.querySelectorAll('.minus');

            plusButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const input = button.previousElementSibling;
                    const currentValue = parseInt(input.value) || 0;
                    const max = parseInt(input.getAttribute('max')) || 10;
                    if (currentValue < max) {
                        input.value = currentValue + 1;
                    }
                });
            });

            minusButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const input = button.nextElementSibling;
                    const currentValue = parseInt(input.value) || 0;
                    const min = parseInt(input.getAttribute('min')) || 0;
                    if (currentValue > min) {
                        input.value = currentValue - 1;
                    }
                });
            });
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <header>
        <h1>Menú My Delights</h1>
    </header>
    <?php
    echo Utilities::renderMenu();
    ?>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Iniciar Sesión</h5>
                </div>
                <div class="modal-body">
                    <input type="text" id="usuario" class="form-control mb-2" placeholder="Usuario">
                    <input type="password" id="contrasena" class="form-control" placeholder="Contraseña">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="iniciarSesion()">Ingresar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <?php foreach ($agrupados as $tipo => $productosTipo): ?>
            <h2><?= htmlspecialchars($tipo) ?></h2>
            <?php foreach ($productosTipo as $producto): ?>
                <div class="menu-item">
                    <img src="<?= htmlspecialchars($producto['foto_url']) ?>"
                        alt="<?= htmlspecialchars($producto['producto_nombre']) ?>">
                    <div>
                        <h3><?= htmlspecialchars($producto['producto_nombre']) ?></h3>
                        <p>Ingredientes: <?= htmlspecialchars($producto['ingredientes']) ?>.</p>
                        <p>Precio: $<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                        <div class="quantity">
                            <button class="minus" aria-label="Decrease">&minus;</button>
                            <input type="number" class="input-box" value="0" min="0" max="10"
                                data-id="<?= $producto['id_producto'] ?>"
                                data-nombre="<?= htmlspecialchars($producto['producto_nombre']) ?>"
                                data-precio="<?= $producto['precio'] ?>">
                            <button class="plus" aria-label="Increase">&plus;</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <button id="btnRealizarPedido" onclick="openLoginModal()" class="btn btn-primary mt-3">Realizar
            Pedido</button>

    </div>
    <a href="Restaurante.php" class="back">Volver al Inicio</a>

    <script>
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

        async function iniciarSesion() {
            const pedido = realizarPedido();
            console.log('Ejecutando Login: ' + JSON.stringify(pedido));
            const usuario = document.getElementById('usuario').value;
            const contrasena = document.getElementById('contrasena').value;

            const data = { usuario, contrasena };
            const method = 'POST';

            const result = await fetch('clientes_api.php', {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'login', user: usuario, password: contrasena })
            });
            console.log('Login Result: ' + result);

            if (result.ok) {
                const json = await result.json();
                if (json) {
                    if (json.rol === 'CLIENTE') {
                        loginModal.hide();
                        console.log('Login Data...');
                        console.log(json);
                        console.log({ clienteId: json.id_cliente, productos: pedido, tipoPedido: 'Domicilio' });

                        fetch('procesar_pedido.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ clienteId: json.id_cliente, productos: pedido, tipoPedido: 'Domicilio' })
                        }).then(response => response.json())
                            .then(data => {
                                console.log('Pasando a Data...');
                                console.log(data);
                                console.log(JSON.parse(JSON.stringify(data)));
                                console.log(JSON.stringify(data));
                                if (data.success) {
                                    alert('Pedido realizado con éxito');
                                    location.reload();
                                } else {
                                    alert('No se pudo realizar el pedido, intente mas tarde');
                                }
                            });

                    } else {
                        alert('no es un cliente');
                    }
                } else {
                    alert('Error al iniciar sesión, proceda a registrarse');
                    return;
                }
            } else {
                alert('Error al iniciar sesión');
                return;
            }
        }

        function openLoginModal() {
            console.log('Abriendo Modal: ' + JSON.stringify(realizarPedido()));
            if (realizarPedido()) {
                loginModal.show();
            }
        }

    </script>
</body>

</html>