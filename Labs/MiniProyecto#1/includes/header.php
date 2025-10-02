<?php
// Incluir el archivo de constantes para poder usar BASE_URL
require_once __DIR__ . '/../config/constants.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Proyecto de Ingeniería Web</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Estilos personalizados (ignorados por ahora, como se solicitó) -->
    <!-- <link href="<?php echo BASE_URL; ?>assets/css/styles.css" rel="stylesheet"> -->
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
            <!-- NOTA: Asegúrate de colocar tu logo en assets/images/logo.png -->
            <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Logo UTP" width="30" height="30" class="d-inline-block align-top me-2">
            <strong>Ingeniería Web</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>">Menú Principal</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container mt-5">