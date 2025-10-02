<?php
/**
 * Archivo de Constantes del Proyecto
 * Mini Proyecto #2 - Ingeniería Web
 * Universidad Tecnológica de Panamá
 */

// Información del Proyecto
const NOMBRE_PROYECTO = 'Mini Proyecto #2 - Sentencias de Control y Clases';
const UNIVERSIDAD = 'Universidad Tecnológica de Panamá';
const FACULTAD = 'Facultad de Ingeniería en Sistemas Computacionales';
const CURSO = 'Ingeniería Web';
const INSTRUCTOR = 'Ing. Irina Fong';

// Información de los Estudiantes
const ESTUDIANTE_1 = 'Estudiante 1';
const CORREO_1 = 'estudiante1@utp.ac.pa';

const ESTUDIANTE_2 = 'Estudiante 2';
const CORREO_2 = 'estudiante2@utp.ac.pa';

// Configuración de la Aplicación
const TIMEZONE = 'America/Panama';
const FECHA_REALIZACION = '2025-09-01';

// Rutas del Proyecto
const PATH_INCLUDES = __DIR__ . '/../includes/';
const PATH_UTILS = __DIR__ . '/../utils/';
const PATH_PROBLEMAS = __DIR__ . '/../problemas/';

// Configuración de Visualización
const DECIMALES_PRECISION = 4;
const MAX_REGISTROS_TABLA = 100;

// Configuración de Zona Horaria
date_default_timezone_set(TIMEZONE);

?>