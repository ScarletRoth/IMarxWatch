<?php
require_once __DIR__ . '/../config/database.php';
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

    /**
     * Handle user login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/login.php');
            exit();
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        // Validation
        if (empty($email) || empty($password)) {
            header('Location: ../views/login.php?error=empty');
            exit();
        }

        // Find user by email
        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            header('Location: ../views/login.php?error=invalid');
            exit();
        }

        // Start session and store user data
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        // Set remember me cookie if checked
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
            // Store token in database (you'd need to add a remember_token field to users table)
        }

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: ../views/admin/dashboard.php');
        } else {
            header('Location: ../views/home.php');
        }
        exit();
    }

    /**
     * Handle user registration
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/signup.php');
            exit();
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $terms = isset($_POST['terms']);

        // Validation
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            header('Location: ../views/signup.php?error=empty');
            exit();
        }

        if (!$terms) {
            header('Location: ../views/signup.php?error=terms');
            exit();
        }

        if ($password !== $confirmPassword) {
            header('Location: ../views/signup.php?error=password_mismatch');
            exit();
        }

        // Check if email already exists
        if ($this->userModel->findByEmail($email)) {
            header('Location: ../views/signup.php?error=email_exists');
            exit();
        }

        // Create user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        if ($this->userModel->create($name, $email, $passwordHash)) {
            header('Location: ../views/login.php?success=registered');
            exit();
        } else {
            header('Location: ../views/signup.php?error=server');
            exit();
        }
    }

    /**
     * Handle user logout
     */
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        // Clear remember me cookie
        setcookie('remember_token', '', time() - 3600, '/');

        header('Location: ../views/login.php');
        exit();
    }
}

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AuthController();
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'login':
            $controller->login();
            break;
        case 'register':
            $controller->register();
            break;
        case 'logout':
            $controller->logout();
            break;
        default:
            header('Location: ../views/login.php');
            exit();
    }
}
