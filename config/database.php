<?php

class Database
{
    private $host = 'localhost';
    private $db_name = 'imarxwatch';
    private $db_user = 'root';
    private $db_password = '';
    private $pdo;

    public function connect()
    {
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4';
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
