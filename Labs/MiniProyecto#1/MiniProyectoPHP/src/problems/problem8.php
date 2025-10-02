<?php include_once '../views/templates/header.php'; ?>

<div class="container">
    <h2 class="text-center">Problema 8: Estación del Año</h2>

    <form method="post">
        <div class="mb-3">
            <label for="fecha" class="form-label">Ingrese una fecha:</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
        </div>
        <button type="submit" class="btn btn-primary">Calcular Estación</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $fecha = $_POST['fecha'];
        $mes = date("m", strtotime($fecha));
        $dia = date("d", strtotime($fecha));
        $estacion = "";

        if (($mes == 3 && $dia >= 21) || ($mes == 4) || ($mes == 5) || ($mes == 6 && $dia <= 20)) {
            $estacion = "Primavera";
        } elseif (($mes == 6 && $dia >= 21) || ($mes == 7) || ($mes == 8) || ($mes == 9 && $dia <= 20)) {
            $estacion = "Verano";
        } elseif (($mes == 9 && $dia >= 21) || ($mes == 10) || ($mes == 11) || ($mes == 12 && $dia <= 20)) {
            $estacion = "Otoño";
        } else {
            $estacion = "Invierno";
        }

        echo "<div class='alert alert-info mt-3'>La estación es: <b>$estacion</b></div>";
    }
    ?>
</div>

<?php include_once '../views/templates/footer.php'; ?>
