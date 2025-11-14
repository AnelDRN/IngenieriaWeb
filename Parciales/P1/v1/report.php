<?php
require_once 'db.php';

$db = new Database();

// 14. Crear un reporte con los datos que se guardarón.
// Se usa un LEFT JOIN para obtener el nombre del país desde la tabla `paises`.
$query = "
    SELECT 
        i.id_inscriptor,
        i.nombre,
        i.apellido,
        i.edad,
        i.sexo,
        p.nombre_pais,
        i.nacionalidad,
        i.observaciones,
        i.fecha_registro
    FROM 
        inscriptores i
    LEFT JOIN 
        paises p ON i.id_pais_residencia = p.id_pais
    ORDER BY 
        i.fecha_registro DESC
";

$inscriptores = $db->fetchAll($query);

// Para cada inscriptor, obtenemos sus temas de interés
foreach ($inscriptores as $key => $inscriptor) {
    $temas_query = "
        SELECT t.nombre_tema 
        FROM inscriptor_temas it
        JOIN temas_tecnologicos t ON it.id_tema = t.id_tema
        WHERE it.id_inscriptor = ?
    ";
    $temas = $db->fetchAll($temas_query, [$inscriptor['id_inscriptor']]);
    // Añadimos los temas al array del inscriptor
    $inscriptores[$key]['temas'] = array_column($temas, 'nombre_tema');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inscriptos - V1</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
        }
        .card-header {
            background-color: #6c757d;
            color: white;
        }
        .footer {
            padding: 1rem 0;
            background-color: #343a40;
            color: white;
            text-align: center;
        }
        .table-responsive {
            margin-top: 1rem;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="card">
            <div class="card-header">
                <h1 class="h3 mb-0">Reporte de Personas Inscritas</h1>
            </div>
            <div class="card-body">
                <a href="index.php" class="btn btn-primary mb-3">Ir al Formulario de Inscripción</a>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nombre Completo</th>
                                <th>Edad</th>
                                <th>Sexo</th>
                                <th>País Residencia</th>
                                <th>Nacionalidad</th>
                                <th>Temas de Interés</th>
                                <th>Observaciones</th>
                                <th>Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($inscriptores)): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No hay registros para mostrar.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($inscriptores as $inscriptor): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($inscriptor['id_inscriptor']) ?></td>
                                        <td><?= htmlspecialchars($inscriptor['nombre'] . ' ' . $inscriptor['apellido']) ?></td>
                                        <td><?= htmlspecialchars($inscriptor['edad']) ?></td>
                                        <td><?= htmlspecialchars($inscriptor['sexo']) ?></td>
                                        <td><?= htmlspecialchars($inscriptor['nombre_pais']) ?></td>
                                        <td><?= htmlspecialchars($inscriptor['nacionalidad']) ?></td>
                                        <td>
                                            <?php if (!empty($inscriptor['temas'])): ?>
                                                <?= htmlspecialchars(implode(', ', $inscriptor['temas'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($inscriptor['observaciones']) ?></td>
                                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($inscriptor['fecha_registro']))) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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