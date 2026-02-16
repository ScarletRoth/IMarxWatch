<?php

class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password_hash;
    public $role;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Find user by email
     * @param string $email
     * @return array|false User data or false if not found
     */
    public function findByEmail($email)
    {
        $query = "SELECT id, name, email, password_hash, role, created_at 
                  FROM {$this->table} 
                  WHERE email = :email 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find user by ID
     * @param int $id
     * @return array|false User data or false if not found
     */
    public function findById($id)
    {
        $query = "SELECT id, name, email, password_hash, role, created_at 
                  FROM {$this->table} 
                  WHERE id = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new user
     * @param string $name
     * @param string $email
     * @param string $passwordHash
     * @param string $role
     * @return bool True on success, false on failure
     */
    public function create($name, $email, $passwordHash, $role = 'customer')
    {
        $query = "INSERT INTO {$this->table} (name, email, password_hash, role) 
                  VALUES (:name, :email, :password_hash, :role)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }

    /**
     * Update user information
     * @param int $id
     * @param array $data
     * @return bool True on success, false on failure
     */
    public function update($id, $data)
    {
        $allowed = ['name', 'email', 'password_hash', 'role'];
        $fields = [];
        $values = [':id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $fields[] = "{$key} = :{$key}";
                $values[":{$key}"] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $query = "UPDATE {$this->table} 
                  SET " . implode(', ', $fields) . " 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($values);
    }

    /**
     * Delete a user
     * @param int $id
     * @return bool True on success, false on failure
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Get all users
     * @param int $limit
     * @param int $offset
     * @return array List of users
     */
    public function getAll($limit = 100, $offset = 0)
    {
        $query = "SELECT id, name, email, role, created_at 
                  FROM {$this->table} 
                  ORDER BY created_at DESC 
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count total users
     * @return int Total number of users
     */
    public function count()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    /**
     * Check if email exists
     * @param string $email
     * @param int|null $excludeId User ID to exclude from check (for updates)
     * @return bool True if exists, false otherwise
     */
    public function emailExists($email, $excludeId = null)
    {
        $query = "SELECT id FROM {$this->table} WHERE email = :email";

        if ($excludeId) {
            $query .= " AND id != :exclude_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);

        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
