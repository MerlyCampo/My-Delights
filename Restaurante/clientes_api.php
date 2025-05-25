<?php
require_once 'Cliente.php';
$clienteModel = new Cliente();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
   case 'GET':
      if (isset($_GET['id_cliente'])) {
         $idCliente = $_GET['id_cliente'];
         // Se llama al método que obtiene los pedidos por cliente
         echo json_encode($clienteModel->consultaPedidos($idCliente));
      } else {
         echo json_encode($clienteModel->obtenerTodos());
      }
      break;

   case 'POST':
      $data = json_decode(file_get_contents('php://input'), true);
      if (isset($data['accion']) && $data['accion'] == 'login') {
         echo json_encode($clienteModel->login($data['user'], $data['password']));
      } else {

         echo json_encode($clienteModel->insertar($data['cedula'], $data['nombre'], $data['apellido'], $data['sexo'], $data['fechaNacimiento'], $data['direccion'], $data['telefono'], $data['email'], $data['tipo_cliente'], $data['tiene_credito']));

      }
      break;

   case 'PUT':
      $data = json_decode(file_get_contents('php://input'), true);

      echo json_encode($clienteModel->actualizar($data['id'], $data['nombre'], $data['apellido'], $data['email'], $data['cedula'], $data['sexo'], $data['fechaNacimiento'], $data['direccion'], $data['telefono'], $data['tipo_cliente'], $data['tiene_credito']));
      break;

   case 'DELETE':
      parse_str(file_get_contents('php://input'), $data);
      echo json_encode($clienteModel->eliminar($data['id']));
      break;
}
?>