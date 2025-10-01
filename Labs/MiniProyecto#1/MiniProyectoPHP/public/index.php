<?php

require_once '../src/utils/Utilities.php';

// Simple router
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$problem_view = '../src/problems/' . $page . '.php';

if ($page === 'home') {
    include_once '../views/home.php';
} elseif (file_exists($problem_view)) {
    include_once $problem_view;
} else {
    // Simple 404 page
    http_response_code(404);
    include_once '../views/templates/header.php';
    echo '<h1 class="text-center">Error 404: Página no encontrada</h1>';
    include_once '../views/templates/footer.php';
}
