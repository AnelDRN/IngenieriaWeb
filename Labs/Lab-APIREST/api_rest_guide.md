# GUÍA DE LABORATORIO: API REST con PHP y Postman

## INFORMACIÓN GENERAL
- **Curso**: Desarrollo de Software VII - Ingeniería Web
- **Instructor**: Ing. Irina Fong
- **Grupos**: ISF131 / ISF132
- **Fecha Límite**: 14 de noviembre de 2025
- **Modalidad**: Individual
- **Puntos Totales**: 100%
- **Video Tutorial**: https://www.youtube.com/watch?v=Y9jkkfGjbzQ

## OBJETIVO PRINCIPAL
Construir una API REST en PHP que permita:
- **POST**: Registrar productos (implementado en clase)
- **GET**: Listar/consultar productos (DEBES IMPLEMENTAR)
- **PUT**: Actualizar productos por ID (DEBES IMPLEMENTAR)
- **Probar** todos los endpoints con Postman

---

## BASE DE DATOS REQUERIDA

Usar la misma tabla `productos` del laboratorio anterior:

```sql
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL,
    producto VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL
);
```

---

## ESTRUCTURA DE ARCHIVOS API REST

```
api-rest-productos/
│
├── Modelo/
│   ├── conexion.php              # Clase DB (conexión PDO)
│   └── Productos.php             # Clase Producto con métodos CRUD
│
├── Controller/
│   └── ProductosController.php   # Controlador con switch para métodos HTTP
│
├── api/
│   └── productos.php             # Endpoint principal que llama al controlador
│
└── .htaccess                     # Configuración de rutas (opcional)
```

---

## ACTIVIDADES A REALIZAR

### ✅ Actividad 1: Implementar método GET
Listar todos los productos o consultar uno específico por ID en `ProductosController.php`

### ✅ Actividad 2: Implementar método PUT
Actualizar un producto existente por ID en `ProductosController.php`

### ✅ Actividad 3: Probar con Postman
Realizar pruebas de los tres métodos (POST, GET, PUT) y documentar resultados

### ✅ Actividad 4: Capturar evidencias
Tomar capturas de pantalla de Postman mostrando las respuestas exitosas

### ✅ Actividad 5: Mostrar resultados al Facilitador
Presentar código y evidencias para obtener la calificación

---

## ESTRUCTURA DEL CONTROLADOR CON SWITCH

### ProductosController.php

```php
<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../Modelo/conexion.php';
require_once '../Modelo/Productos.php';

// Obtener el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Centralizar con SWITCH según método HTTP
switch($method) {
    case 'POST':
        registrarProducto();
        break;
        
    case 'GET':
        obtenerProductos();
        break;
        
    case 'PUT':
        actualizarProducto();
        break;
        
    case 'DELETE':
        eliminarProducto();
        break;
        
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
}

// FUNCIÓN POST - Registrar producto (EJEMPLO GUIADO)
function registrarProducto() {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Validar datos
    if(!isset($data['codigo']) || !isset($data['producto']) || 
       !isset($data['precio']) || !isset($data['cantidad'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos'
        ]);
        return;
    }
    
    try {
        $producto = new Producto();
        $producto->codigo = $data['codigo'];
        $producto->producto = $data['producto'];
        $producto->precio = $data['precio'];
        $producto->cantidad = $data['cantidad'];
        
        if($producto->guardar()) {
            http_response_code(201); // Código 201: Recurso creado
            echo json_encode([
                'success' => true,
                'message' => 'Producto registrado exitosamente'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar producto'
            ]);
        }
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

// FUNCIÓN GET - Listar productos (DEBES IMPLEMENTAR)
function obtenerProductos() {
    try {
        // Si hay parámetro ?id=X en la URL, buscar producto específico
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            // TODO: Buscar producto por ID
            // Retornar un solo producto
        } else {
            // TODO: Listar todos los productos
            // Retornar array de productos
        }
        
        http_response_code(200); // Código 200: OK
        echo json_encode([
            'success' => true,
            'data' => $productos // Array de productos
        ]);
        
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

// FUNCIÓN PUT - Actualizar producto (DEBES IMPLEMENTAR)
function actualizarProducto() {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Validar que venga el ID
    if(!isset($data['id'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID del producto es requerido'
        ]);
        return;
    }
    
    try {
        // TODO: Crear objeto Producto con datos recibidos
        // TODO: Llamar método editar()
        // TODO: Retornar respuesta
        
        http_response_code(200); // Código 200: Actualizado
        echo json_encode([
            'success' => true,
            'message' => 'Producto actualizado exitosamente'
        ]);
        
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
?>
```

---

## MÉTODOS REQUERIDOS EN CLASE PRODUCTO

### Modelo/Productos.php

```php
<?php
require_once 'conexion.php';

class Producto {
    public $id;
    public $codigo;
    public $producto;
    public $precio;
    public $cantidad;
    
    // Método POST - Guardar (ya implementado)
    public function guardar() {
        $db = DB::getInstance();
        $sql = "INSERT INTO productos (codigo, producto, precio, cantidad) 
                VALUES (?, ?, ?, ?)";
        $params = [$this->codigo, $this->producto, $this->precio, $this->cantidad];
        return $db->insertSeguro($sql, $params);
    }
    
    // Método GET - Listar todos (DEBES IMPLEMENTAR)
    public static function listarTodos() {
        $db = DB::getInstance();
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        // TODO: Ejecutar query y retornar resultados
        return $db->query($sql);
    }
    
    // Método GET - Buscar por ID (DEBES IMPLEMENTAR)
    public static function buscarPorId($id) {
        $db = DB::getInstance();
        $sql = "SELECT * FROM productos WHERE id = ?";
        // TODO: Ejecutar query y retornar resultado
        return $db->query($sql, [$id]);
    }
    
    // Método PUT - Actualizar (DEBES IMPLEMENTAR)
    public function editar() {
        $db = DB::getInstance();
        $sql = "UPDATE productos 
                SET codigo = ?, producto = ?, precio = ?, cantidad = ? 
                WHERE id = ?";
        $params = [$this->codigo, $this->producto, $this->precio, 
                   $this->cantidad, $this->id];
        return $db->updateSeguro($sql, $params);
    }
    
    // Método DELETE - Eliminar (OPCIONAL)
    public static function eliminar($id) {
        $db = DB::getInstance();
        $sql = "DELETE FROM productos WHERE id = ?";
        return $db->query($sql, [$id]);
    }
}
?>
```

---

## CÓDIGOS DE ESTADO HTTP IMPORTANTES

| Código | Significado | Cuándo usarlo |
|--------|-------------|---------------|
| **200** | OK | Operación exitosa (GET, PUT) |
| **201** | Created | Recurso creado exitosamente (POST) |
| **400** | Bad Request | Datos inválidos o incompletos |
| **404** | Not Found | Recurso no encontrado |
| **405** | Method Not Allowed | Método HTTP no soportado |
| **500** | Internal Server Error | Error del servidor |

### Función http_response_code()

```php
// Establecer código de respuesta HTTP
http_response_code(201); // Recurso creado

// Esta función le indica al cliente el resultado de la operación
// Código 201 = "Se creó un nuevo recurso exitosamente"
```

**Importancia**: Los códigos HTTP permiten que el cliente sepa cómo manejar la respuesta sin necesidad de analizar el contenido.

---

## PRUEBAS CON POSTMAN

### 1. Método POST - Registrar Producto

**Configuración**:
- **Método**: POST
- **URL**: `http://localhost/api-rest-productos/Controller/ProductosController.php`
- **Headers**: 
  - `Content-Type: application/json`
- **Body** (raw JSON):
```json
{
    "codigo": "A001",
    "producto": "Mouse óptico",
    "precio": 10.50,
    "cantidad": 5
}
```

**Respuesta Esperada** (201 Created):
```json
{
    "success": true,
    "message": "Producto registrado exitosamente"
}
```

---

### 2. Método GET - Listar Todos los Productos

**Configuración**:
- **Método**: GET
- **URL**: `http://localhost/api-rest-productos/Controller/ProductosController.php`
- **Headers**: Ninguno especial

**Respuesta Esperada** (200 OK):
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "codigo": "A001",
            "producto": "Mouse óptico",
            "precio": "10.50",
            "cantidad": 5
        },
        {
            "id": 2,
            "codigo": "A002",
            "producto": "Teclado mecánico",
            "precio": "45.00",
            "cantidad": 3
        }
    ]
}
```

---

### 3. Método GET - Buscar Producto por ID

**Configuración**:
- **Método**: GET
- **URL**: `http://localhost/api-rest-productos/Controller/ProductosController.php?id=1`
- **Params**: 
  - `id: 1`

**Respuesta Esperada** (200 OK):
```json
{
    "success": true,
    "data": {
        "id": 1,
        "codigo": "A001",
        "producto": "Mouse óptico",
        "precio": "10.50",
        "cantidad": 5
    }
}
```

---

### 4. Método PUT - Actualizar Producto

**Configuración**:
- **Método**: PUT
- **URL**: `http://localhost/api-rest-productos/Controller/ProductosController.php`
- **Headers**: 
  - `Content-Type: application/json`
- **Body** (raw JSON):
```json
{
    "id": 1,
    "codigo": "A001",
    "producto": "Mouse óptico RGB",
    "precio": 15.99,
    "cantidad": 10
}
```

**Respuesta Esperada** (200 OK):
```json
{
    "success": true,
    "message": "Producto actualizado exitosamente"
}
```

---

## CONCEPTOS CLAVE API REST

### ¿Qué es una API REST?

**REST** (Representational State Transfer) es un estilo arquitectural para diseñar servicios web que utilizan HTTP como protocolo de comunicación.

### Principios REST:
1. **Stateless**: Cada petición es independiente
2. **Cliente-Servidor**: Separación clara de responsabilidades
3. **Métodos HTTP estándar**: GET, POST, PUT, DELETE
4. **Formato JSON**: Intercambio de datos en formato JSON
5. **Códigos de estado**: Uso correcto de códigos HTTP

### Métodos HTTP:

| Método | Operación | Descripción |
|--------|-----------|-------------|
| **GET** | READ | Obtener recursos (no modifica datos) |
| **POST** | CREATE | Crear nuevos recursos |
| **PUT** | UPDATE | Actualizar recursos existentes |
| **DELETE** | DELETE | Eliminar recursos |

---

## LECTURA DE DATOS EN PHP SEGÚN MÉTODO

### GET (parámetros en URL)
```php
// URL: api.php?id=5&nombre=Juan
$id = $_GET['id'];
$nombre = $_GET['nombre'];
```

### POST/PUT (datos en body JSON)
```php
// Leer JSON del cuerpo de la petición
$data = json_decode(file_get_contents("php://input"), true);
$codigo = $data['codigo'];
$producto = $data['producto'];
```

---

## HEADERS CORS PARA API

```php
// Permitir acceso desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Métodos HTTP permitidos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

// Headers permitidos en las peticiones
header("Access-Control-Allow-Headers: Content-Type");

// Tipo de contenido de la respuesta
header("Content-Type: application/json");
```

**Importante**: Estos headers deben estar al inicio del archivo PHP antes de cualquier salida.

---

## VALIDACIÓN DE DATOS EN API

### Validaciones Básicas:

```php
// Verificar campos requeridos
if(!isset($data['codigo']) || empty($data['codigo'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El código es requerido'
    ]);
    return;
}

// Validar tipos de datos
if(!is_numeric($data['precio']) || $data['precio'] <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El precio debe ser un número positivo'
    ]);
    return;
}

// Validar longitud
if(strlen($data['codigo']) > 20) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'El código no puede exceder 20 caracteres'
    ]);
    return;
}
```

---

## MANEJO DE ERRORES

### Try-Catch en todas las funciones:

```php
try {
    // Código que puede generar excepciones
    $resultado = $producto->guardar();
    
    if($resultado) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Operación exitosa'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error en la operación'
        ]);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
```

---

## RÚBRICA DE EVALUACIÓN

| Criterio | Puntaje |
|----------|---------|
| Puntualidad | 15% |
| Método GET implementado correctamente | 20% |
| Método PUT funcional y probado | 20% |
| Centralización con switch | 10% |
| Uso adecuado de Postman | 15% |
| Código organizado y documentado | 10% |
| Implementación de control de errores | 10% |
| **TOTAL** | **100%** |

---

## CHECKLIST DE ENTREGA

- [ ] Base de datos `productos` creada y funcionando
- [ ] Clase DB con métodos insertSeguro, updateSeguro, query
- [ ] Clase Producto con métodos: guardar, listarTodos, buscarPorId, editar
- [ ] ProductosController.php con switch para métodos HTTP
- [ ] Método POST funcionando (registrar producto)
- [ ] Método GET funcionando (listar todos)
- [ ] Método GET con ID funcionando (buscar específico)
- [ ] Método PUT funcionando (actualizar producto)
- [ ] Headers CORS configurados
- [ ] Códigos HTTP correctos (200, 201, 400, 404, 500)
- [ ] Validaciones de datos implementadas
- [ ] Manejo de errores con try-catch
- [ ] Pruebas en Postman realizadas
- [ ] Capturas de pantalla de respuestas exitosas
- [ ] Código documentado con comentarios
- [ ] Demostración al facilitador completada

---

## ESTRUCTURA DE RESPUESTAS JSON

### Respuesta Exitosa:
```json
{
    "success": true,
    "message": "Operación exitosa",
    "data": { /* datos del recurso */ }
}
```

### Respuesta con Error:
```json
{
    "success": false,
    "message": "Descripción del error",
    "errors": ["error1", "error2"]
}
```

---

## TIPS PARA POSTMAN

1. **Crear una colección** para organizar todas tus peticiones
2. **Guardar cada petición** con un nombre descriptivo
3. **Usar variables de entorno** para la URL base
4. **Documentar cada endpoint** con descripción y ejemplos
5. **Exportar la colección** para compartir o respaldar

### Variables de Entorno en Postman:
```
base_url: http://localhost/api-rest-productos/Controller
```

Uso en peticiones:
```
{{base_url}}/ProductosController.php
```

---

## RECURSOS NECESARIOS

- **XAMPP/WAMP**: Servidor Apache + MySQL + PHP
- **Postman**: https://www.postman.com/downloads/
- **Video Tutorial**: https://www.youtube.com/watch?v=Y9jkkfGjbzQ
- **Editor de Código**: VS Code (recomendado)
- **Extensión Chrome**: JSON Viewer (opcional)

---

## PASOS DE IMPLEMENTACIÓN

1. **Reutilizar** la clase DB y Producto del laboratorio anterior
2. **Crear** ProductosController.php con estructura switch
3. **Implementar** método POST (guiado en clase)
4. **Probar** POST con Postman
5. **Implementar** método GET (listar todos)
6. **Implementar** método GET con ID (buscar específico)
7. **Probar** ambos GET con Postman
8. **Implementar** método PUT (actualizar)
9. **Probar** PUT con Postman
10. **Capturar** pantallas de todas las pruebas
11. **Documentar** código con comentarios
12. **Mostrar** resultados al facilitador

---

## EJEMPLO COMPLETO DE PETICIÓN POSTMAN

### POST - Crear Producto

**Request**:
```
POST http://localhost/api-rest-productos/Controller/ProductosController.php
Content-Type: application/json

{
    "codigo": "A001",
    "producto": "Mouse óptico",
    "precio": 10.50,
    "cantidad": 5
}
```

**Response** (201 Created):
```json
{
    "success": true,
    "message": "Producto registrado exitosamente"
}
```

---

## DIFERENCIAS ENTRE LABORATORIO CRUD Y API REST

| Aspecto | CRUD con Fetch | API REST |
|---------|----------------|----------|
| Cliente | Navegador web | Cualquier cliente HTTP |
| Formato | FormData | JSON puro |
| Identificación | Campo 'Accion' | Método HTTP |
| Respuesta | HTML/SweetAlert | JSON únicamente |
| Testing | Navegador | Postman/Insomnia |
| Uso | Aplicación web | Servicios externos |

---

**FECHA DE ENTREGA**: 14 de noviembre de 2025  
**INSTRUCTOR**: Ing. Irina Fong  
**GRUPOS**: ISF131 / ISF132  
**VIDEO GUÍA**: https://www.youtube.com/watch?v=Y9jkkfGjbzQ