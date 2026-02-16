<?php

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = trim($path ?? '/', '/');

if ($path === '' || $path === 'index.php') {
    $path = 'home';
}

switch ($path) {
    case 'home':
        require __DIR__ . '/../views/home.php';
        break;
    case 'login':
        require __DIR__ . '/../views/login.php';
        break;
    case 'signup':
        require __DIR__ . '/../views/signup.php';
        break;
    case 'auth/login':
    case 'auth/register':
    case 'auth/logout':
        require __DIR__ . '/../controllers/AuthController.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AuthController();
            if ($path === 'auth/login') {
                $controller->login();
            } elseif ($path === 'auth/register') {
                $controller->register();
            } else {
                $controller->logout();
            }
        } else {
            if ($path === 'auth/logout') {
                $controller = new AuthController();
                $controller->logout();
            } else {
                header('Location: /login');
                exit();
            }
        }
        break;
    default:
        http_response_code(404);
        echo '404 Not Found';
        break;
}
