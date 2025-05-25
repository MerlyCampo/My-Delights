<?php

class Utilities
{
   public static function renderMenu()
   {
      return '
      
         <nav>
            <ul>
               <li><a href="Restaurante.php">Inicio</a></li>
               <li><a href="Menu.php">Menú</a></li>
               <li><a href="Servicios.php">Servicios</a></li>
               <li><a href="Cotización.php">Lista de Pedidos</a></li>
               <li><a href="Clientes.php">Gestión de Clientes</a></li>
            </ul>
         </nav>';
   }
}

?>