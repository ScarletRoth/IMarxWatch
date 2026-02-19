<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/SessionManager.php';

SessionManager::init();

$path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$path = parse_url($path, PHP_URL_PATH);
$segments = array_filter(explode('/', $path));
$segments = array_values($segments);

if (empty($segments)) {
    $segments = ['home'];
}

$route = $segments[0] ?? 'home';
$action = $segments[1] ?? null;
$param = $segments[2] ?? null;

switch ($route) {
    case 'home':
    case 'index.php':
        require VIEWS_PATH . '/home.php';
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../controllers/AuthController.php';
            $controller = new AuthController();
            $controller->login();
        } else {
            require VIEWS_PATH . '/login.php';
        }
        break;
    case 'signup':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../controllers/AuthController.php';
            $controller = new AuthController();
            $controller->register();
        } else {
            require VIEWS_PATH . '/signup.php';
        }
        break;
    case 'logout':
        require __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'movies':
        require __DIR__ . '/../controllers/MovieController.php';
        $controller = new MovieController();

        if ($action === null) {
            $controller->listMovies();
        } elseif ($action && is_numeric($action)) {
            $controller->detail($action);
        } else {
            header('Location: /movies');
        }
        break;
    case 'bookings':
        require __DIR__ . '/../controllers/BookingController.php';
        $controller = new BookingController();

        if ($action && is_numeric($action)) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->store();
            } else {
                $controller->show($action);
            }
        } else {
            header('Location: /movies');
        }
        break;
    case 'user':
        require __DIR__ . '/../controllers/AuthController.php';
        require __DIR__ . '/../controllers/BookingController.php';

        if (!SessionManager::isAuthenticated()) {
            header('Location: /login');
            exit();
        }

        if ($action === 'profile') {
            $authController = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($param === 'update') {
                    $authController->updateProfile();
                } elseif ($param === 'delete') {
                    $authController->deleteAccount();
                }
            } else {
                $authController->profile();
            }
        } elseif ($action === 'bookings') {
            $bookingController = new BookingController();
            $bookingController->userBookings();
        } elseif ($action === 'bookings' && $param === 'cancel' && isset($segments[3])) {
            $bookingController = new BookingController();
            $bookingController->cancel($segments[3]);
        } else {
            header('Location: /user/profile');
        }
        break;
    case 'admin':
        require __DIR__ . '/../controllers/AdminController.php';
        require __DIR__ . '/../controllers/MovieController.php';
        require __DIR__ . '/../controllers/BookingController.php';

        if (!SessionManager::isAdmin()) {
            $_SESSION['error'] = 'Access denied. Admin privileges required.';
            header('Location: /');
            exit();
        }

        $adminController = new AdminController();
        $movieController = new MovieController();
        $bookingController = new BookingController();

        if ($action === 'dashboard') {
            $adminController->dashboard();
        }
        elseif ($action === 'movies') {
            if ($param === 'create') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $movieController->store();
                } else {
                    $movieController->create();
                }
            } elseif (is_numeric($param)) {
                if ($segments[3] === 'edit') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $movieController->update($param);
                    } else {
                        $movieController->edit($param);
                    }
                } elseif ($segments[3] === 'delete' || $_SERVER['REQUEST_METHOD'] === 'POST') {
                    $movieController->delete($param);
                }
            } else {
                $adminController->listMovies();
            }
        }
        elseif ($action === 'users') {
            if ($param && is_numeric($param) && $segments[3] === 'delete') {
                $adminController->deleteUser($param);
            } else {
                $adminController->listUsers();
            }
        }
        elseif ($action === 'sessions') {
            if ($param === 'create') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $adminController->storeSession();
                } else {
                    $adminController->createSession();
                }
            } elseif (is_numeric($param) && $segments[3] === 'delete') {
                $adminController->deleteSession($param);
            } else {
                $adminController->listSessions();
            }
        }
        elseif ($action === 'bookings') {
            if ($param && is_numeric($param) && $segments[3] === 'delete') {
                $bookingController->adminDelete($param);
            } else {
                $bookingController->adminList();
            }
        } else {
            $adminController->dashboard();
        }
        break;
    default:
        http_response_code(404);
        require VIEWS_PATH . '/404.php';
        break;
}
