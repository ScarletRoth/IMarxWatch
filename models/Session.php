<?php

class SessionModel
{
    private $conn;
    private $table = 'sessions';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll($limit = null, $offset = 0)
    {
        $query = "SELECT s.*, m.title as movie_title, r.name as room_name 
                  FROM {$this->table} s
                  JOIN movies m ON s.movie_id = m.id
                  JOIN rooms r ON s.room_id = r.id
                  ORDER BY s.starts_at DESC";

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
        $query = "SELECT s.*, m.title as movie_title, m.duration_minutes, r.name as room_name, r.seats_total
                  FROM {$this->table} s
                  JOIN movies m ON s.movie_id = m.id
                  JOIN rooms r ON s.room_id = r.id
                  WHERE s.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSessionsByMovieId($movieId, $upcomingOnly = true)
    {
        $query = "SELECT s.*, r.name as room_name, r.seats_total
                  FROM {$this->table} s
                  JOIN rooms r ON s.room_id = r.id
                  WHERE s.movie_id = :movie_id";

        if ($upcomingOnly) {
            $query .= " AND s.starts_at > NOW()";
        }

        $query .= " ORDER BY s.starts_at ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($movieId, $roomId, $startsAt, $price)
    {
        $query = "INSERT INTO {$this->table} (movie_id, room_id, starts_at, price)
                  VALUES (:movie_id, :room_id, :starts_at, :price)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->bindParam(':starts_at', $startsAt);
        $stmt->bindParam(':price', $price);

        return $stmt->execute();
    }

    public function update($id, $data)
    {
        $allowed = ['movie_id', 'room_id', 'starts_at', 'price'];
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

    public function getAvailableSeatsCount($sessionId)
    {
        $query = "SELECT COUNT(*) as available
                  FROM seats s
                  WHERE s.room_id = (SELECT room_id FROM {$this->table} WHERE id = :session_id)
                  AND s.id NOT IN (
                      SELECT seat_id FROM booking_seats WHERE session_id = :session_id
                  )";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['available'];
    }

    public function getBookedSeats($sessionId)
    {
        $query = "SELECT bs.seat_id, s.seat_row, s.seat_number
                  FROM booking_seats bs
                  JOIN seats s ON bs.seat_id = s.id
                  WHERE bs.session_id = :session_id
                  ORDER BY s.seat_row, s.seat_number";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSeats($sessionId)
    {
        $query = "SELECT s.id, s.seat_row, s.seat_number
                  FROM seats s
                  WHERE s.room_id = (SELECT room_id FROM {$this->table} WHERE id = :session_id)
                  ORDER BY s.seat_row, s.seat_number";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUpcomingSessions($limit = 10)
    {
        $query = "SELECT s.*, m.title as movie_title, r.name as room_name
                  FROM {$this->table} s
                  JOIN movies m ON s.movie_id = m.id
                  JOIN rooms r ON s.room_id = r.id
                  WHERE s.starts_at > NOW()
                  ORDER BY s.starts_at ASC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
