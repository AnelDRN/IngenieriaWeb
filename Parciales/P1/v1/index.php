<?php
require_once 'db.php';

$db = new Database();
$conn = $db->getConnection();

// Cargar países y temas para los controles del formulario
$paises = $db->fetchAll("SELECT * FROM paises ORDER BY nombre_pais ASC");
$temas = $db->fetchAll("SELECT * FROM temas_tecnologicos ORDER BY nombre_tema ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción al Evento iTECH - V1</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
        }
        .footer {
            padding: 1rem 0;
            background-color: #343a40;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="card">
            <div class="card-header">
                <h1 class="h3 mb-0">Formulario de Inscripción iTECH</h1>
            </div>
            <div class="card-body">
                <p class="card-text">Por favor, complete el siguiente formulario para registrarse en el evento.</p>
                
                <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                    <div class="alert alert-success">¡Inscripción guardada correctamente!</div>
                <?php endif; ?>
                <?php if (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> Hubo problemas al guardar su inscripción.
                        <?php if (isset($_GET['message'])): ?>
                            <br><?= htmlspecialchars(urldecode($_GET['message'])) ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form action="save.php" method="POST">
                    <!-- Fecha del Formulario (Campo Oculto) -->
                    <input type="hidden" name="fecha_formulario" value="<?= date('Y-m-d H:i:s') ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edad" class="form-label">Edad</label>
                            <input type="number" class="form-control" id="edad" name="edad" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sexo</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sexo" id="sexo_masculino" value="Masculino" required>
                                    <label class="form-check-label" for="sexo_masculino">Masculino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sexo" id="sexo_femenino" value="Femenino">
                                    <label class="form-check-label" for="sexo_femenino">Femenino</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="pais_residencia" class="form-label">País de Residencia</label>
                            <select class="form-select" id="pais_residencia" name="pais_residencia" required>
                                <option value="" disabled selected>Seleccione un país...</option>
                                <?php foreach ($paises as $pais): ?>
                                    <option value="<?= $pais['id_pais'] ?>"><?= htmlspecialchars($pais['nombre_pais']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nacionalidad" class="form-label">Nacionalidad</label>
                            <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tema(s) Tecnológico(s) de Interés</label>
                            <div>
                                <?php foreach ($temas as $tema): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="temas[]" value="<?= $tema['id_tema'] ?>" id="tema_<?= $tema['id_tema'] ?>">
                                        <label class="form-check-label" for="tema_<?= $tema['id_tema'] ?>">
                                            <?= htmlspecialchars($tema['nombre_tema']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="observaciones" class="form-label">Observaciones o Consulta</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button class="w-100 btn btn-primary btn-lg" type="submit">Inscribirse</button>
                </form>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="report.php" class="btn btn-secondary">Ver Reporte de Inscriptos</a>
        </div>
    </div>

    <footer class="footer mt-auto">
        <div class="container">
            <span>© <?= date('Y') ?> iTECH. All rights reserved.</span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>