<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/SessionManager.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../models/Booking.php';

class AdminController
{
    private $db;
    private $userModel;
    private $movieModel;
    private $sessionModel;
    private $bookingModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
        $this->movieModel = new Movie($this->db);
        $this->sessionModel = new SessionModel($this->db);
        $this->bookingModel = new Booking($this->db);
    }

    public function dashboard()
    {
        SessionManager::init();
        SessionManager::requireAdmin();

        try {
            $totalUsers = $this->userModel->count();
            $totalMovies = $this->movieModel->count();
            $upcomingSessions = $this->sessionModel->getUpcomingSessions(5);
            
            include VIEWS_PATH . '/admin/dashboard.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading dashboard: ' . $e->getMessage();
            header('Location: /');
            exit();
        }
    }

    public function listMovies()
    {
        SessionManager::init();
        SessionManager::requireAdmin();

        try {
            $movies = $this->movieModel->getAll();
            include VIEWS_PATH . '/admin/movies/list.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading movies: ' . $e->getMessage();
            header('Location: /admin/dashboard');
            exit();
        }
    }

    public function listUsers()
    {
        SessionManager::init();
        SessionManager::requireAdmin();

        try {
            $users = $this->userModel->getAll();
            include VIEWS_PATH . '/admin/users/list.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading users: ' . $e->getMessage();
            header('Location: /admin/dashboard');
            exit();
        }
    }

    public function deleteUser($userId)
    {
        SessionManager::init();
        SessionManager::requireAdmin();

        try {
            if ($userId == $_SESSION['user_id']) {
                $_SESSION['error'] = 'You cannot delete your own account';
                header('Location: /admin/users');
                exit();
            }

            if ($this->userModel->delete($userId)) {
                $_SESSION['success'] = 'User deleted successfully';
            } else {
                $_SESSION['error'] = 'Failed to delete user';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: /admin/users');
        exit();
    }

    public function listSessions()
    {
        SessionManager::init();
        SessionManager::requireAdmin();

        try {
            $sessions = $this->sessionModel->getAll();
            include VIEWS_PATH . '/admin/sessions/list.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading sessions: ' . $e->getMessage();
            header('Location: /admin/dashboard');
            exit();
        }
    }

    public function createSession()
    {
        SessionManager::init();
        SessionManager::requireAdmin();

        try {
            $movies = $this->movieModel->getAll();
            $query = "SELECT * FROM rooms ORDER BY name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            include VIEWS_PATH . '/admin/sessions/form.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /admin/sessions');
            exit();
        }
    }

    public function storeSession()
    {
        SessionManager::init();
        SessionManager::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/sessions');
            exit();
        }

        try {
            $movieId = (int)($_POST['movie_id'] ?? 0);
            $roomId = (int)($_POST['room_id'] ?? 0);
            $startsAt = $_POST['starts_at'] ?? '';
            $price = (float)($_POST['price'] ?? 0);

            if (!$movieId || !$roomId || !$startsAt || $price <= 0) {
                $_SESSION['error'] = 'Please fill in all fields';
                header('Location: /admin/sessions/create');
                exit();
            }

            if ($this->sessionModel->create($movieId, $roomId, $startsAt, $price)) {
                $_SESSION['success'] = 'Session created successfully';
                header('Location: /admin/sessions');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to create session';
                header('Location: /admin/sessions/create');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /admin/sessions/create');
            exit();
        }
    }

    public function deleteSession($sessionId)
    {
        SessionManager::init();
        SessionManager::requireAdmin();

        try {
            if ($this->sessionModel->delete($sessionId)) {
                $_SESSION['success'] = 'Session deleted successfully';
            } else {
                $_SESSION['error'] = 'Failed to delete session';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: /admin/sessions');
        exit();
    }
}
