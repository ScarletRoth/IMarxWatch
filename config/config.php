<?php

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}

define('APP_NAME', 'IMarxWatch');
define('APP_URL', 'http://localhost:8000');
define('APP_ENV', 'development');

define('SESSION_TIMEOUT', 1800);
define('SESSION_COOKIE_SECURE', false);
define('SESSION_COOKIE_HTTPONLY', true);
define('SESSION_COOKIE_SAMESITE', 'Lax');

define('PASSWORD_ALGO', PASSWORD_DEFAULT);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900);

define('VIEWS_PATH', __DIR__ . '/../views');
define('LAYOUT_PATH', __DIR__ . '/../views/layout');

if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/error.log');
}

date_default_timezone_set('Europe/Paris');

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Content-Type: text/html; charset=UTF-8');

function html($text)
{
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

function isAuthenticated()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAuth()
{
    if (!isAuthenticated()) {
        header('Location: /login?error=unauthorized');
        exit();
    }
}

function requireAdmin()
{
    requireAuth();
    if (!isAdmin()) {
        header('Location: /?error=forbidden');
        exit();
    }
}

function currentUser()
{
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'name' => $_SESSION['user_name'] ?? null,
        'email' => $_SESSION['user_email'] ?? null,
        'role' => $_SESSION['user_role'] ?? null
    ];
}
