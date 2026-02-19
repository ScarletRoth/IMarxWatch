<?php
require_once __DIR__  . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/SessionManager.php';
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private $db;
    private $userModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit();
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            header('Location: /login?error=empty');
            exit();
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            header('Location: /login?error=invalid');
            exit();
        }

        SessionManager::init();
        SessionManager::login($user);

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (86400 * 30), '/', '', SESSION_COOKIE_SECURE, SESSION_COOKIE_HTTPONLY);
        }

        if ($user['role'] === 'admin') {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /');
        }
        exit();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /signup');
            exit();
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $terms = isset($_POST['terms']);

        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            header('Location: /signup?error=empty');
            exit();
        }

        if (!$terms) {
            header('Location: /signup?error=terms');
            exit();
        }

        if ($password !== $confirmPassword) {
            header('Location: /signup?error=password_mismatch');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /signup?error=invalid_email');
            exit();
        }

        if (strlen($password) < 8) {
            header('Location: /signup?error=weak_password');
            exit();
        }

        if ($this->userModel->findByEmail($email)) {
            header('Location: /signup?error=email_exists');
            exit();
        }

        $passwordHash = password_hash($password, PASSWORD_ARGON2ID);

        if ($this->userModel->create($name, $email, $passwordHash)) {
            header('Location: /login?success=registered');
        } else {
            header('Location: /signup?error=registration_failed');
        }
        exit();
    }

    public function logout()
    {
        SessionManager::init();
        SessionManager::logout();

        header('Location: /login?success=logout');
        exit();
    }

    public function profile()
    {
        SessionManager::init();
        if (!SessionManager::isAuthenticated()) {
            header('Location: /login');
            exit();
        }

        $user = SessionManager::getCurrentUser();
        $userDetails = $this->userModel->findById($user['id']);

        include VIEWS_PATH . '/user/profile.php';
    }

    public function updateProfile()
    {
        SessionManager::init();
        if (!SessionManager::isAuthenticated()) {
            header('Location: /login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /user/profile');
            exit();
        }

        try {
            $userId = $_SESSION['user_id'];
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (empty($name) || empty($email)) {
                $_SESSION['error'] = 'Name and email are required';
                header('Location: /user/profile');
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Invalid email format';
                header('Location: /user/profile');
                exit();
            }

            if ($this->userModel->emailExists($email, $userId)) {
                $_SESSION['error'] = 'Email already in use';
                header('Location: /user/profile');
                exit();
            }

            $data = ['name' => $name, 'email' => $email];

            if ($this->userModel->update($userId, $data)) {
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['success'] = 'Profile updated successfully';
                header('Location: /user/profile');
            } else {
                $_SESSION['error'] = 'Failed to update profile';
                header('Location: /user/profile');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /user/profile');
        }
        exit();
    }

    public function deleteAccount()
    {
        SessionManager::init();
        if (!SessionManager::isAuthenticated()) {
            header('Location: /login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /user/profile');
            exit();
        }

        try {
            $userId = $_SESSION['user_id'];
            $password = $_POST['password'] ?? '';

            if (empty($password)) {
                $_SESSION['error'] = 'Password required to delete account';
                header('Location: /user/profile');
                exit();
            }

            $user = $this->userModel->findById($userId);
            if (!$user || !password_verify($password, $user['password_hash'])) {
                $_SESSION['error'] = 'Invalid password';
                header('Location: /user/profile');
                exit();
            }

            if ($this->userModel->delete($userId)) {
                SessionManager::logout();
                $_SESSION['success'] = 'Account deleted successfully';
                header('Location: /login?success=account_deleted');
            } else {
                $_SESSION['error'] = 'Failed to delete account';
                header('Location: /user/profile');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /user/profile');
        }
        exit();
    }
}
