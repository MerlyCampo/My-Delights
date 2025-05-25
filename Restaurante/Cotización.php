<?php
require_once 'Utilities.php';

?>

<!DOCTYPE html>
<html lang="es">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - My Delights</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .modal-backdrop.show {
            opacity: 0.5;
        }

        .btn {
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: scale(1.1);
        }
    </style>
    <link rel=" stylesheet" href="styles.css">
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
                    <button class="btn btn-primary" onclick="iniciarSesion()">Ingresar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cliente -->
    <div class="modal fade" id="clienteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clienteModalLabel">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- <div class="modal-body">
                    <input type="hidden" id="clienteId">
                    <input type="text" id="cedula" class="form-control mb-2" placeholder="Cédula" required>
                    <input type="text" id="nombre" class="form-control mb-2" placeholder="Nombre" required>
                    <input type="text" id="apellido" class="form-control mb-2" placeholder="Apellido" required>
                    <select id="sexo" class="form-control mb-2" required>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                    <input type="date" id="fechaNacimiento" class="form-control mb-2" required>
                    <input type="text" id="direccion" class="form-control mb-2" placeholder="Dirección" required>
                    <input type="tel" id="telefono" class="form-control mb-2" placeholder="Teléfono Celular" required>
                    <input type="email" id="email" class="form-control mb-2" placeholder="Correo Electrónico" required>
                    <select id="tipo_cliente" class="form-control mb-2" required>
                        <option value="Nuevo">Nuevo</option>
                        <option value="Casual">Casual</option>
                        <option value="Permanente">Permanente</option>
                    </select>
                    <select id="tiene_credito" class="form-control mb-2" required>
                        <option value="1">Si</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="guardarCliente()">Guardar</button>
                </div> -->
            </div>
        </div>
    </div>

    <header>
        <h1>Gestión de Pedidos</h1>
    </header>

    <?php
    echo Utilities::renderMenu();
    ?>
    <div class="container">
        <h2 style="text-align: center">Pedidos</h2>


        <table class="table table-hover table-striped align-middle text-center shadow-sm rounded overflow-hidden">
            <thead class="table-dark">
                <tr>
                    <th>N° de pedido</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Valor total</th>
                    <th>Descuento aplicado</th>
                    <th>Tipo de pedido</th>
                </tr>
            </thead>
            <tbody id="tablaClientes" class="bg-white"></tbody>
        </table>

    </div>
    <a href="Restaurante.php" class="back">Volver al Inicio</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        const clienteModal = new bootstrap.Modal(document.getElementById('clienteModal'));

        window.onload = () => loginModal.show();

        async function iniciarSesion() {
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
                const json = await result.json();;
                if (json) {
                    if (json.rol === 'CLIENTE') {
                        loginModal.hide();
                        //document.getElementById('contenido').classList.remove('d-none');
                        console.log('Cargando pedidos con cliente: ' + JSON.stringify(json));
                        cargarClientes(json.id_cliente);
                    } else {
                        alert('no es un cliente');
                    }
                } else {
                    alert('Error al iniciar sesión');
                    return;
                }
            } else {
                alert('Error al iniciar sesión');
                return;
            }
        }

        async function cargarClientes(id_cliente) {
            console.log('Cliente Id: ' + id_cliente);
            // const res = fetch('clientes_api.php?id_cliente='.id_cliente);
            const res = await fetch(`clientes_api.php?id_cliente=${id_cliente}`);
            console.log(res);
            if (res.ok) {
                const json = await res.json();;
                if (json) {
                    console.log(json);
                    renderizarTabla(json);
                }
            }

        }


        function renderizarTabla(pedidos) {
            console.log(pedidos);
            const tabla = document.getElementById('tablaClientes');
            tabla.innerHTML = '';
            pedidos.forEach(c => {
                tabla.innerHTML += `
          <tr>
            <td>${c.id_pedido}</td>
            <td>${c.nombres}</td>
            <td>${c.telefono}</td>
            <td>${c.total}</td>
            <td>${c.descuento_aplicado}</td>
            <td>${c.tipo_pedido}</td>

           
          </tr>`;
            });
        }





        //     const tabla = document.getElementById('tablaClientes');
        //     tabla.innerHTML = '';
        //     clientes.forEach(c => {
        //         tabla.innerHTML += `
        //   <tr>
        //     <td>${c.nombre}</td>
        //     <td>${c.apellido}</td>
        //     <td>${c.email}</td>
        //     <td>${c.telefono}</td>
        //     <td>${c.direccion}</td>
        //     <td>${c.tipo_cliente}</td>


        //   </tr>`;
        //     });
        // }

        function abrirModalAgregar() {
            document.getElementById('clienteModalLabel').textContent = 'Agregar Cliente';
            document.getElementById('clienteId').value = '';
            document.getElementById('nombre').value = '';
            document.getElementById('apellido').value = '';
            document.getElementById('email').value = '';
            document.getElementById('cedula').value = '';
            document.getElementById('sexo').value = 'Masculino';
            document.getElementById('fechaNacimiento').value = '';
            document.getElementById('direccion').value = '';
            document.getElementById('telefono').value = '';
            document.getElementById('tipo_cliente').value = 'Nuevo';
            document.getElementById('tiene_credito').value = '1';
            clienteModal.show();
        }

        function editarCliente(cliente) {
            document.getElementById('clienteModalLabel').textContent = 'Editar Cliente';
            document.getElementById('clienteId').value = cliente.id_cliente;
            document.getElementById('nombre').value = cliente.nombre;
            document.getElementById('apellido').value = cliente.apellido;
            document.getElementById('email').value = cliente.email;
            document.getElementById('cedula').value = cliente.cedula;
            document.getElementById('sexo').value = cliente.sexo;
            document.getElementById('fechaNacimiento').value = cliente.fecha_nacimiento;
            document.getElementById('direccion').value = cliente.direccion;
            document.getElementById('telefono').value = cliente.telefono;
            document.getElementById('tipo_cliente').value = cliente.tipo_cliente;
            document.getElementById('tiene_credito').value = cliente.tiene_credito;
            clienteModal.show();
        }

        async function guardarCliente() {
            const id = document.getElementById('clienteId').value;
            const nombre = document.getElementById('nombre').value;
            const apellido = document.getElementById('apellido').value;
            const email = document.getElementById('email').value;
            const cedula = document.getElementById('cedula').value;
            const sexo = document.getElementById('sexo').value;
            const fechaNacimiento = document.getElementById('fechaNacimiento').value;
            const direccion = document.getElementById('direccion').value;
            const telefono = document.getElementById('telefono').value;
            const tipo_cliente = document.getElementById('tipo_cliente').value;
            const tiene_credito = document.getElementById('tiene_credito').value;
            const data = { id, cedula, nombre, apellido, email, sexo, fechaNacimiento, direccion, telefono, tipo_cliente, tiene_credito };
            const method = id ? 'PUT' : 'POST';

            console.log(data);

            const final = await fetch('clientes_api.php', {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            console.log('Respuesta: ' + JSON.stringify(await final.json()));


            clienteModal.hide();
            cargarClientes();
        }

        async function eliminarCliente(id) {
            if (confirm('¿Estás seguro de eliminar este cliente?')) {
                await fetch('clientes_api.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + id
                });
                cargarClientes();
            }
        }
    </script>

</body>

</html>