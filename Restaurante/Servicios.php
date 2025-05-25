<?php
require_once 'ServicioEvento.php';
require_once 'Utilities.php';

$servicioModel = new ServicioEvento();
$servicios = $servicioModel->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - My Delights</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: #f4f4f4;
        }

        header {
            background: #050505;
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

        .service-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .service-item img {
            width: 120px;
            height: auto;
            border-radius: 10px;
            margin-right: 20px;
        }

        .service-item h3 {
            margin: 0;
        }

        .service-item p {
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
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

    </script>
</head>

<body>


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
                    <button class="btn btn-primary" onclick="realizarLogin()">Ingresar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cotizarModal" tabindex="-1" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cotizar Servicio</h5>
                </div>
                <div class="modal-body">
                    <p><strong>Evento:</strong> <span id="eventoSeleccionado"></span></p>
                    <label for="personas">Cantidad de personas</label>
                    <input type="number" id="personas" class="form-control" min="1" value="1">
                    <p class="mt-2">Precio estimado: <span id="totalEstimado"></span></p>
                    <input type="hidden" id="clientId" name="clientId">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="btnEnviarCotizacion">Enviar cotización</button>
                </div>
            </div>
        </div>
    </div>


    <header>
        <h1>Servicios de My Delights</h1>
    </header>

    <?php
    echo Utilities::renderMenu();
    ?>


    <div class="container">
        <h2>Eventos y Banquetes</h2>
        <?php foreach ($servicios as $servicio): ?>
            <div class="service-item">
                <img src="<?= htmlspecialchars($servicio['foto_url']) ?>"
                    alt="<?= htmlspecialchars($servicio['tipo_evento']) ?>">
                <div>
                    <h3><?= htmlspecialchars($servicio['tipo_evento']) ?></h3>
                    <p><?= htmlspecialchars($servicio['descripcion']) ?></p>
                    <p>Precio base: $<?= number_format($servicio['precio_base'], 0, ',', '.') ?></p>
                    <p>Por persona: $<?= number_format($servicio['precio_por_persona'], 0, ',', '.') ?></p>
                    <button class="btn btn-warning cotizar-btn" data-id="<?= $servicio['id_servicio'] ?>"
                        data-tipo="<?= htmlspecialchars($servicio['tipo_evento']) ?>"
                        data-precio="<?= $servicio['precio_base'] ?>" data-persona="<?= $servicio['precio_por_persona'] ?>">
                        Cotizar
                    </button>
                </div>
            </div>
        <?php endforeach;
        ?>
    </div>


    <script>

        document.querySelectorAll('.cotizar-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                servicioSeleccionado = {
                    id: btn.dataset.id,
                    tipo: btn.dataset.tipo,
                    precio_base: parseInt(btn.dataset.precio),
                    precio_persona: parseInt(btn.dataset.persona)
                };

                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            });
        });

        function realizarLogin() {
            const usuario = document.getElementById('usuario').value;
            const contrasena = document.getElementById('contrasena').value;

            fetch('clientes_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'login', user: usuario, password: contrasena })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.rol === 'CLIENTE') {
                        bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();
                        abrirCotizacion(data.id_cliente);
                    } else {
                        alert('Credenciales incorrectas');
                    }
                });
        }

        function abrirCotizacion(clienteId) {
            document.getElementById('eventoSeleccionado').textContent = servicioSeleccionado.tipo;

            calcularTotal();

            const cotizarModal = new bootstrap.Modal(document.getElementById('cotizarModal'));
            cotizarModal.show();

            document.getElementById('personas').addEventListener('input', calcularTotal);
            document.getElementById('clientId').value = clienteId;
        }

        function calcularTotal() {
            const personas = parseInt(document.getElementById('personas').value) || 0;
            const total = servicioSeleccionado.precio_base + (personas * servicioSeleccionado.precio_persona);
            document.getElementById('totalEstimado').textContent = `$${total.toLocaleString('es-CO')}`;
        }

        document.getElementById('btnEnviarCotizacion').addEventListener('click', () => {
            const personas = parseInt(document.getElementById('personas').value) || 0;
            const clienteId = document.getElementById('clientId').value;

            const total = servicioSeleccionado.precio_base + (personas * servicioSeleccionado.precio_persona);
            console.log(JSON.stringify({
                id_cliente: clienteId,
                id_servicio: servicioSeleccionado.id,
                personas: personas,
                total_estimado: total
            }));
            fetch('cotizar_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_cliente: clienteId,
                    id_servicio: servicioSeleccionado.id,
                    personas: personas,
                    total_estimado: total
                })
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        alert('Cotización enviada correctamente');
                        bootstrap.Modal.getInstance(document.getElementById('cotizarModal')).hide();
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(err => {
                    alert('Error de red al enviar cotización');
                    console.error(err);
                });
        });
    </script>


    </script>

    <a href="Restaurante.php" class="back">Volver al Inicio</a>
</body>

</html>