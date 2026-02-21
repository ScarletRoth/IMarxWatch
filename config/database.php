<?php

class Database
{
    private $host;
    private $db_name;
    private $db_user;
    private $db_password;
    private $charset;
    private $pdo;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'imarxwatch';
        $this->db_user = getenv('DB_USER') ?: 'root';
        $this->db_password = getenv('DB_PASSWORD') ?: '';
        $this->charset = getenv('DB_CHARSET') ?: 'utf8mb4';
    }

    public function connect()
    {
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=' . $this->charset;
            $this->pdo = new PDO($dsn, $this->db_user, $this->db_password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            die('Erreur de connexion: ' . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connect();
    }
}
