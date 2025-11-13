<?php
header("Content-Type: application/json");

require_once 'Modelo/Productos.php';

// Preparamos una respuesta base
$response = [
    'success' => false,
    'message' => 'Acción no reconocida.',
    'accion' => 'desconocida',
    'errors' => [],
    'data' => null
];

// La guía especifica 'Accion' con 'A' mayúscula, pero también verificamos 'accion' por si se usa GET para listar.
$accion = $_POST['Accion'] ?? $_GET['accion'] ?? null;
$response['accion'] = $accion;

try {
    switch ($accion) {
        case 'Guardar':
            $producto = new Producto($_POST);
            $errors = $producto->validar();

            // Validación adicional: código único
            if (Producto::codigoExiste($producto->codigo)) {
                $errors[] = "El código '{$producto->codigo}' ya está registrado.";
            }

            if (!empty($errors)) {
                $response['message'] = 'Error de validación.';
                $response['errors'] = $errors;
            } else {
                if ($producto->guardar()) {
                    $response['success'] = true;
                    $response['message'] = 'Producto guardado con éxito.';
                } else {
                    $response['message'] = 'Error al guardar el producto en la base de datos.';
                }
            }
            break;

        case 'Modificar':
            $producto = new Producto($_POST);
            $errors = $producto->validar();

            // Validación adicional: código único (excluyendo el ID actual)
            if (Producto::codigoExiste($producto->codigo, $producto->id)) {
                $errors[] = "El código '{$producto->codigo}' ya está registrado en otro producto.";
            }
            
            if (empty($producto->id)) {
                $errors[] = 'No se proporcionó un ID para modificar.';
            }

            if (!empty($errors)) {
                $response['message'] = 'Error de validación.';
                $response['errors'] = $errors;
            } else {
                if ($producto->editar()) {
                    $response['success'] = true;
                    $response['message'] = 'Producto modificado con éxito.';
                } else {
                    $response['message'] = 'Error al modificar el producto.';
                }
            }
            break;

        case 'Buscar':
            $codigo = $_POST['codigo'] ?? '';
            if (empty($codigo)) {
                $response['message'] = 'No se proporcionó un código para buscar.';
            } else {
                $productoData = Producto::buscar($codigo);
                if ($productoData) {
                    $response['success'] = true;
                    $response['message'] = 'Producto encontrado.';
                    $response['data'] = $productoData;
                } else {
                    $response['message'] = "No se encontró ningún producto con el código '{$codigo}'.";
                }
            }
            break;
        
        case 'Listar':
            // Esta acción no está en el switch de la guía, pero es necesaria.
            $productos = Producto::listarTodos();
            $response['success'] = true;
            $response['message'] = 'Listado de productos.';
            $response['data'] = $productos;
            break;

        default:
            // El mensaje de acción no reconocida ya está definido por defecto.
            break;
    }
} catch (Throwable $e) {
    // Captura cualquier error inesperado (ej. fallo de conexión a BD)
    $response['message'] = 'Ocurrió un error inesperado en el servidor.';
    // En modo debug, podríamos añadir el error real:
    // $response['errors'] = [$e->getMessage()];
}

// Siempre se devuelve una respuesta JSON.
echo json_encode($response);