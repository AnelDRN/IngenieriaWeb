
# Laboratorio #2: Implementación de un Sistema de Login en Laravel

Este repositorio documenta el proceso de desarrollo y configuración de un sistema de autenticación de usuarios para el curso de **Ingeniería Web**. `LoginLaravel`, fue construido utilizando el framework Laravel y el paquete de scaffolding `laravel/ui` con Bootstrap.

---

## 1. Requisitos Previos

Para ejecutar este proyecto, es necesario contar con un entorno de desarrollo que cumpla con los siguientes requisitos:

*   **Servidor Web:** Apache (puedes usar un cluster como XAMPP o WAMP)
*   **PHP:** Versión 8.0 o superior.(Se uso 8.4)
*   **Composer:** Gestor de dependencias para PHP.
*   **Base de Datos:** Un servidor de MySQL.
*   **Node.js y NPM:** Para la compilación de assets de frontend.
*   **Laravel Installer:** La herramienta global de Composer para crear nuevos proyectos Laravel.

---

## 2. Proceso de Instalación y Configuración

A continuación se detalla la secuencia de comandos utilizada para la creación del proyecto, instalación de dependencias y configuración del módulo de autenticación.

### 2.1. Creación del Proyecto Laravel

Se utilizó el instalador global de Laravel para generar la estructura inicial del proyecto:

```bash
laravel new LoginLaravel
```

No es necesario ejecutarlos, ya que el repositorio ya contiene la estructura y los archivos necesarios.

### 2.2. Configuración del Entorno y Base de Datos

Antes de ejecutar las migraciones, se configuró el archivo `.env` con las credenciales de la base de datos local.

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loginlaravel
DB_USERNAME=root
DB_PASSWORD=[DBPASSWORD]
```
Asegurate colocar las credenciales correctas.Una vez configurado, ejecuta este comando para crear la base de datos:

```bash
php artisan migrate
```

### 2.3. Instalación del Scaffolding de Autenticación (`laravel/ui`)

Se integró el paquete `laravel/ui` para generar las vistas y rutas de autenticación con Bootstrap.


```bash
# 1. Requerir el paquete a través de Composer
composer require laravel/ui

# 2. Generar el scaffolding básico con Bootstrap
php artisan ui bootstrap

# 3. Añadir las vistas y rutas de autenticación (login, registro, etc.)
php artisan ui bootstrap --auth
```
No hay necesidad de ejecutarlos, ya estan configurados dentro del repositorio.

### 2.4. Compilación de Assets de Frontend

Finalmente, se instalaron las dependencias de Node.js y se compilaron los archivos CSS y JavaScript para que el frontend se visualizara correctamente.

```bash
# Instalar dependencias definidas en package.json
npm install

# Compilar los assets para desarrollo
npm run dev
```
No hay necesdidad de ejecutarlos, ya estan configurados dentro del repositorio.

---
### 2.5. Ejecutar el Servidor

Despues de haber realizado las configuraciones pertinentes, el servidor es inicializado con:

```bash
# Inicializa el servidor de desarrollo
composer run dev
```

---
## 3. Arquitectura MVC

El proyecto sigue la estructura **Modelo-Vista-Controlador**:
*   **Modelos (`app/Models`):** El modelo `User.php` gestiona la interacción con la tabla `users`.
*   **Vistas (`resources/views`):** El comando `php artisan ui bootstrap --auth` generó las vistas necesarias en la carpeta `resources/views/auth`, incluyendo `login.blade.php` y `register.blade.php`.
*   **Controladores (`app/Http/Controllers`):** La lógica para el registro, inicio y cierre de sesión se encuentra en los controladores dentro de `app/Http/Controllers/Auth`.

---

## 4. Resultado Final

A continuación, se presenta una captura de pantalla del formulario de inicio de sesión funcionando correctamente en el navegador:

_**[AQUÍ INSERTA TU CAPTURA DE PANTALLA]**_

---

## 5. Dificultades y Soluciones

Durante el desarrollo de este laboratorio, me encontré con los siguientes desafíos:

*   **Dificultad 1:** Tiempo de espera de aproximadamente 10 minutos de finalización para Laravel new LaravelProject. 
    *   **Solución:** La carpeta de ejecución de php se encontraba en una SD removible. Al momento de ejecutar los comandos de laravel y llamar a php, la lectura y escritura de archivos era lenta. Basto con cambiar php al disco local, hacer la reconfiguración respectiva y el tiempo de ejecución bajo considerablemente. 

*   **Dificultad 2:** Falta de Node package manager (npm), necesario para la ejecución base de Laravel new LaravelProject.
    *   **Solución:** Instalar npm y añadirlo al path para que la terminal lo reconozca cuando sea llamado por Laravel durante el proceso de crear un nuevo proyecto.

*   **Dificultad 3:** Falta de Node package manager (npm), necesario para la ejecución base de Laravel new LaravelProject.
    *   **Solución:** Instalar npm y añadirlo al path para que la terminal lo reconozca cuando sea llamado por Laravel durante el proceso de crear un nuevo proyecto.

*   **Dificultad 4:** Ejecución anormal de los comandos necesarios para crear el login dentro de Laravel
    *   **Solución:** La dirección que contenía el proyecto de Laravel era D:\Mino\Proyectos\Didácticos\IngenieriaWeb\LaravelLogin, “Didácticos” contenía una tilde lo que causa conflictos con Windows y composer. No podría ejecutar comandos como composer require laravel/ui.  

    Después de la corregir la dirección decidí ejecutar los comandos desde el powershell como administrador y la ejecución fue amena. 


---

## 6. Referencias

Para completar esta tarea, se consultaron los siguientes recursos:

1.  [Laravel](https://laravel.com)
2.  [[php](https://www.php.net)
3.  [Composer](https://getcomposer.org)
4. [apache](https://httpd.apache.org)
5. [mysql](https://www.mysql.com)

---

*   **Desarrollado por:** Anel Ruiz
*   **Correo:** anel.ruiz2@utp.ac.pa
*   **Curso:** Ingeniería Web
*   **Instructor:** Ing. Irina Fong
*   **Fecha de Ejecución:** 27 de septiembre de 2025

```