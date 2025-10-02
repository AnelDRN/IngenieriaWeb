<?php include_once '../views/templates/header.php'; ?>

<div class="container">
    <h2 class="text-center">Problema 9: Potencias de un Número</h2>

    <form method="post">
        <div class="mb-3">
            <label for="numero" class="form-label">Ingrese un número (1-9):</label>
            <input type="number" class="form-control" id="numero" name="numero" min="1" max="9" required>
        </div>
        <button type="submit" class="btn btn-primary">Generar Potencias</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $numero = intval($_POST['numero']);

        echo "<h4 class='mt-4'>Las 15 primeras potencias de $numero son:</h4>";
        echo "<ul class='list-group mt-2'>";
        for ($i = 1; $i <= 15; $i++) {
            $resultado = pow($numero, $i);
            echo "<li class='list-group-item'>$numero ^ $i = $resultado</li>";
        }
        echo "</ul>";
    }
    ?>
</div>

<?php include_once '../views/templates/footer.php'; ?>
