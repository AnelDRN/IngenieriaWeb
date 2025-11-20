<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// api/productos.php

// Este archivo es el único punto de entrada a la API para los productos.
// Su única responsabilidad es llamar al controlador.

require_once '../Controller/ProductosController.php';
