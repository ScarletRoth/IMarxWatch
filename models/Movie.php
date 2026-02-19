<?php

class Movie
{
    private $conn;
    private $table = 'movies';

    public $id;
    public $title;
    public $description;
    public $duration_minutes;
    public $rating;
    public $poster_url;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll($limit = null, $offset = 0)
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);

        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ', ') as genres
                  FROM {$this->table} m
                  LEFT JOIN movie_genres mg ON m.id = mg.movie_id
                  LEFT JOIN genres g ON mg.genre_id = g.id
                  WHERE m.id = :id
                  GROUP BY m.id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $description, $duration_minutes, $rating = null, $poster_url = null)
    {
        $query = "INSERT INTO {$this->table} 
                  (title, description, duration_minutes, rating, poster_url) 
                  VALUES (:title, :description, :duration_minutes, :rating, :poster_url)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':duration_minutes', $duration_minutes, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':poster_url', $poster_url);

        return $stmt->execute();
    }

    public function update($id, $data)
    {
        $allowed = ['title', 'description', 'duration_minutes', 'rating', 'poster_url'];
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

        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";

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

    public function count()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    public function getMoviesWithUpcomingSessions()
    {
        $query = "SELECT DISTINCT m.* 
                  FROM {$this->table} m
                  INNER JOIN sessions s ON m.id = s.movie_id
                  WHERE s.starts_at > NOW()
                  ORDER BY m.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addGenre($movieId, $genreId)
    {
        $query = "INSERT INTO movie_genres (movie_id, genre_id) VALUES (:movie_id, :genre_id)
                  ON DUPLICATE KEY UPDATE genre_id = genre_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->bindParam(':genre_id', $genreId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
