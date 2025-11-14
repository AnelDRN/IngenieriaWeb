<?php
require_once 'db.php';

// Función para redirigir con un mensaje de error
function redirect_with_error($message) {
    header('Location: index.php?status=error&message=' . urlencode($message));
    exit();
}

// Función para capitalizar la primera letra
function capitalizar($string) {
    return mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- 15. Validación de los datos del lado de PHP ---
    $errors = [];
    if (empty(trim($_POST['nombre']))) {
        $errors[] = "El nombre es obligatorio.";
    }
    if (empty(trim($_POST['apellido']))) {
        $errors[] = "El apellido es obligatorio.";
    }
    if (empty($_POST['edad']) || !filter_var($_POST['edad'], FILTER_VALIDATE_INT) || $_POST['edad'] <= 0) {
        $errors[] = "La edad debe ser un número válido y positivo.";
    }
    if (empty($_POST['sexo'])) {
        $errors[] = "Debe seleccionar un sexo.";
    }
    if (empty($_POST['pais_residencia'])) {
        $errors[] = "Debe seleccionar un país de residencia.";
    }
    if (empty(trim($_POST['nacionalidad']))) {
        $errors[] = "La nacionalidad es obligatoria.";
    }
    // La fecha y las observaciones no son obligatorias o se autogeneran
    // El tema tecnológico no es obligatorio según el enunciado (no se especifica)

    if (!empty($errors)) {
        redirect_with_error(implode(' ', $errors));
    }

    // --- Preparación de datos ---
    // 16. Al guardarse los nombres y apellidos deben empezar en mayúscula.
    $nombre = capitalizar(trim($_POST['nombre']));
    $apellido = capitalizar(trim($_POST['apellido']));
    $edad = (int)$_POST['edad'];
    $sexo = $_POST['sexo'];
    $id_pais_residencia = (int)$_POST['pais_residencia'];
    $nacionalidad = capitalizar(trim($_POST['nacionalidad']));
    $temas = isset($_POST['temas']) ? $_POST['temas'] : [];
    $observaciones = trim($_POST['observaciones']);
    $fecha_formulario = $_POST['fecha_formulario']; // Ya viene del formulario

    $db = new Database();
    $conn = $db->getConnection();

    // Iniciar transacción para asegurar la integridad de los datos
    $conn->beginTransaction();

    try {
        // Insertar en la tabla `inscriptores`
        $sql_inscriptor = "INSERT INTO inscriptores (nombre, apellido, edad, sexo, id_pais_residencia, nacionalidad, observaciones, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_inscriptor = $conn->prepare($sql_inscriptor);
        
        $stmt_inscriptor->execute([
            $nombre,
            $apellido,
            $edad,
            $sexo,
            $id_pais_residencia,
            $nacionalidad,
            $observaciones,
            $fecha_formulario
        ]);

        // Obtener el ID del último registro insertado
        $id_inscriptor = $conn->lastInsertId();

        // Insertar en la tabla `inscriptor_temas` si se seleccionaron temas
        if (!empty($temas) && $id_inscriptor) {
            $sql_temas = "INSERT INTO inscriptor_temas (id_inscriptor, id_tema) VALUES (?, ?)";
            $stmt_temas = $conn->prepare($sql_temas);

            foreach ($temas as $id_tema) {
                $stmt_temas->execute([$id_inscriptor, (int)$id_tema]);
            }
        }

        // Si todo fue bien, confirmar la transacción
        $conn->commit();

        // Redirigir con mensaje de éxito
        header('Location: index.php?status=success');
        exit();

    } catch (Exception $e) {
        // Si algo falló, revertir la transacción
        $conn->rollBack();
        
        // Redirigir con un mensaje de error genérico
        redirect_with_error("Ocurrió un error en la base de datos: " . $e->getMessage());
    }

} else {
    // Si no es un POST, redirigir al index
    header('Location: index.php');
    exit();
}
?>