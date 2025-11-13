#  Sistema CRUD de Gestión de Productos

Sistema completo de gestión de productos con operaciones CRUD (Crear, Leer, Actualizar) utilizando tecnologías web modernas.

## Integrantes del Grupo

- **Estudiante 1**: [Valeria Agrazal 8-1019-684]
- **Estudiante 2**: [Anel Ruiz 8-1015-1859]

**Curso**: Ingeniería Web  
**Instructor**: Ing. Irina Fong  
**Grupo**: ISF132  
**Fecha de Entrega**: 14 de noviembre de 2025

---

## Descripción del Proyecto

Sistema web de gestión de productos que permite realizar las siguientes operaciones:

- **Guardar** nuevos productos en la base de datos
- **Editar** productos existentes
- **Buscar** productos por código
- **Listar** todos los productos registrados

El sistema implementa comunicación asíncrona mediante **Fetch API**, arquitectura **PHP orientada a objetos**, y una interfaz responsiva con **Bootstrap 5**.

---

## Tecnologías Utilizadas

### Frontend
- **HTML5** - Estructura semántica
- **CSS3 / Bootstrap 5** - Diseño responsivo y componentes UI
- **JavaScript ES6+** - Lógica del cliente
- **Fetch API** - Peticiones asíncronas
- **SweetAlert2** - Alertas modernas y amigables

### Backend
- **PHP 7.4+** - Programación Orientada a Objetos
- **PDO** - Capa de abstracción de base de datos
- **MySQL 5.7+** - Sistema de gestión de base de datos

### Arquitectura
- **MVC (Modelo-Vista-Controlador)** - Separación de responsabilidades
- **Patrón Singleton** - Gestión de conexión a BD
- **Prepared Statements** - Seguridad contra SQL Injection

---

## Estructura del Proyecto
laboratorio-crud/
│
├── 📄 index.html                 # Interfaz principal del usuario
├── 📄 script.js                  # Lógica JavaScript y Fetch API
├── 📄 registrar.php              # Controlador central con switch
├── 📄 README.md                  # Este archivo
│
├──  Modelo/
│   ├── conexion.php              # Clase DB - Conexión singleton
│   └── Productos.php             # Clase Producto - Lógica de negocio
│
└──  sql/
    └── database.sql              # Script de creación de BD y tabla
```

---

## Estructura de Base de Datos

### Tabla: `productos`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT AUTO_INCREMENT PRIMARY KEY | Identificador único |
| `codigo` | VARCHAR(20) NOT NULL | Código del producto |
| `producto` | VARCHAR(100) NOT NULL | Nombre del producto |
| `precio` | DECIMAL(10,2) NOT NULL | Precio unitario |
| `cantidad` | INT NOT NULL | Cantidad en inventario |

---

## Instalación y Configuración

### Prerrequisitos

- **XAMPP** o **WAMP** instalado
- **PHP 7.4** o superior
- **MySQL 5.7** o superior
- Navegador web moderno (Chrome, Firefox, Edge)

### Pasos de Instalación

1. **Clonar o descargar el repositorio**
```bash
   git clone [URL-de-tu-repositorio]
   cd laboratorio-crud
```

2. **Crear la base de datos**
   - Abrir **phpMyAdmin** (`http://localhost/phpmyadmin`)
   - Crear una nueva base de datos llamada `productosdb`
   - Importar el archivo `sql/database.sql` o ejecutar:
```sql
   CREATE DATABASE productosdb;
   USE productosdb;
   
   CREATE TABLE productos (
       id INT AUTO_INCREMENT PRIMARY KEY,
       codigo VARCHAR(20) NOT NULL,
       producto VARCHAR(100) NOT NULL,
       precio DECIMAL(10,2) NOT NULL,
       cantidad INT NOT NULL
   );
```

3. **Configurar credenciales de base de datos**
   - Abrir `Modelo/conexion.php`
   - Modificar las credenciales según tu configuración:
```php
   private $host = "localhost";
   private $dbname = "productosdb";
   private $username = "root";
   private $password = "";  // Tu contraseña de MySQL
```

4. **Copiar archivos al servidor local**
   - Copiar la carpeta del proyecto a:
     - **XAMPP**: `C:/xampp/htdocs/laboratorio-crud/`
     - **WAMP**: `C:/wamp64/www/laboratorio-crud/`

5. **Iniciar el servidor**
   - Iniciar Apache y MySQL desde el panel de control de XAMPP/WAMP

6. **Acceder a la aplicación**
   - Abrir navegador y acceder a:
```
   http://localhost/laboratorio-crud/index.html
```

---

##  Funcionalidades Implementadas

### 1. Guardar Producto 
- Validación de campos obligatorios
- Verificación de código único
- Inserción segura con prepared statements
- Confirmación con SweetAlert2

### 2. Editar Producto 
- Búsqueda de producto por código
- Precarga de datos en formulario
- Actualización de información
- Validación de existencia

### 3. Buscar Producto 
- Búsqueda por código de producto
- Visualización de información completa
- Manejo de productos no encontrados

### 4. Listar Productos 
- Tabla dinámica de productos
- Actualización automática después de operaciones
- Diseño responsivo con Bootstrap

---

## Características de Seguridad

- **Prepared Statements (PDO)** - Prevención de SQL Injection
- **Validación en servidor** - PHP valida todos los datos
- **Validación en cliente** - JavaScript valida antes de enviar
- **Respuestas JSON estructuradas** - Formato estandarizado
- **Manejo de errores** - Try-catch en PHP y JavaScript
- **Content-Type headers** - Encabezados correctos para JSON

---

## Validaciones Implementadas

### Cliente (JavaScript)
- Campos no vacíos
- Formato numérico para precio y cantidad
- Valores positivos
- Longitud de caracteres

### Servidor (PHP)
- Tipos de datos correctos
- Campos obligatorios presentes
- Código único en operación Guardar
- Existencia de producto en operación Editar
- Sanitización de entradas

---

## Interfaz de Usuario

### Componentes Bootstrap Utilizados
- **Forms** - Inputs y formularios
- **Buttons** - Botones de acción
- **Alerts** - Mensajes de información
- **Tables** - Listado de productos
- **Cards** - Contenedores de información
- **Grid System** - Diseño responsivo

### Alertas SweetAlert2
- Confirmaciones de éxito
- Mensajes de error
- Validaciones visuales
- Diseño moderno y amigable

**Última actualización**: Noviembre 2025  
**Versión**: 1.0.0