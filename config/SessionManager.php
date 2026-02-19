<?php

class SessionManager
{
    public static function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', SESSION_COOKIE_SECURE);
            ini_set('session.cookie_httponly', SESSION_COOKIE_HTTPONLY);
            ini_set('session.cookie_samesite', SESSION_COOKIE_SAMESITE);
            ini_set('session.gc_maxlifetime', SESSION_TIMEOUT);
            session_start();
        }
        self::checkTimeout();
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
            session_regenerate_id(true);
        } elseif (time() - $_SESSION['last_regeneration'] > 300) {
            $_SESSION['last_regeneration'] = time();
            session_regenerate_id(true);
        }
    }

    private static function checkTimeout()
    {
        if (isset($_SESSION['user_id'])) {
            if (!isset($_SESSION['last_activity'])) {
                $_SESSION['last_activity'] = time();
            }
            if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
                session_unset();
                session_destroy();
                if (php_sapi_name() !== 'cli') {
                    header('Location: /login?error=session_expired');
                    exit();
                }
            } else {
                $_SESSION['last_activity'] = time();
            }
        }
    }

    public static function login($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        $_SESSION['last_regeneration'] = time();
        session_regenerate_id(true);
    }

    public static function logout()
    {
        session_unset();
        session_destroy();

        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
    }

    public static function isAuthenticated()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public static function getCurrentUser()
    {
        if (self::isAuthenticated()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'role' => $_SESSION['user_role']
            ];
        }
        return null;
    }

    public static function isAdmin()
    {
        return self::isAuthenticated() && $_SESSION['user_role'] === 'admin';
    }

    public static function requireAuth()
    {
        if (!self::isAuthenticated()) {
            header('Location: /login?error=unauthorized');
            exit();
        }
    }

    public static function requireAdmin()
    {
        self::requireAuth();
        if (!self::isAdmin()) {
            header('Location: /?error=forbidden');
            exit();
        }
    }
}
