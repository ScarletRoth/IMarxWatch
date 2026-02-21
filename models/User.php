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

    public function create($name, $email, $passwordHash, $role = 'customer')
    {
        $query = "INSERT INTO {$this->table} (name, email, password_hash, role) 
                  VALUES (:name, :email, :password_hash, :role)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }

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

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

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

    public function count()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

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

    public function createRememberToken($userId)
    {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', time() + (86400 * 30));

        $query = "INSERT INTO remember_tokens (user_id, token_hash, expires_at) 
                  VALUES (:user_id, :token_hash, :expires_at)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':token_hash', $tokenHash);
        $stmt->bindParam(':expires_at', $expiresAt);

        if ($stmt->execute()) {
            return $token;
        }

        return false;
    }

    public function getUserByRememberToken($token)
    {
        $tokenHash = hash('sha256', $token);
        
        $query = "SELECT u.id, u.name, u.email, u.password_hash, u.role, u.created_at 
                  FROM {$this->table} u
                  INNER JOIN remember_tokens rt ON u.id = rt.user_id
                  WHERE rt.token_hash = :token_hash 
                  AND rt.expires_at > NOW()
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token_hash', $tokenHash);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteRememberToken($token)
    {
        $tokenHash = hash('sha256', $token);
        
        $query = "DELETE FROM remember_tokens WHERE token_hash = :token_hash";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token_hash', $tokenHash);

        return $stmt->execute();
    }

    public function deleteAllRememberTokens($userId)
    {
        $query = "DELETE FROM remember_tokens WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function cleanupExpiredTokens()
    {
        $query = "DELETE FROM remember_tokens WHERE expires_at < NOW()";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }
}
