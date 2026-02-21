<?php

class Booking
{
    private $conn;
    private $table = 'bookings';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getById($id)
    {
        $query = "SELECT b.*, m.title as movie_title, s.starts_at, s.price
                  FROM {$this->table} b
                  JOIN sessions s ON b.session_id = s.id
                  JOIN movies m ON s.movie_id = m.id
                  WHERE b.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserBookings($userId, $limit = null, $offset = 0)
    {
        $query = "SELECT b.id, b.created_at, b.status, b.total_price, b.seats_count,
                         m.title as movie_title, s.starts_at, s.price,
                         r.name as room_name
                  FROM {$this->table} b
                  JOIN sessions s ON b.session_id = s.id
                  JOIN movies m ON s.movie_id = m.id
                  JOIN rooms r ON s.room_id = r.id
                  WHERE b.user_id = :user_id
                  ORDER BY s.starts_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($userId, $sessionId, $seatsCount, $totalPrice)
    {
        $query = "INSERT INTO {$this->table} (user_id, session_id, seats_count, total_price, status)
                  VALUES (:user_id, :session_id, :seats_count, :total_price, 'confirmed')";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
        $stmt->bindParam(':seats_count', $seatsCount, PDO::PARAM_INT);
        $stmt->bindParam(':total_price', $totalPrice);

        return $stmt->execute();
    }

    public function getLastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    public function addSeat($bookingId, $sessionId, $seatId)
    {
        $query = "INSERT INTO booking_seats (booking_id, session_id, seat_id)
                  VALUES (:booking_id, :session_id, :seat_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        $stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
        $stmt->bindParam(':seat_id', $seatId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getBookingSeats($bookingId)
    {
        $query = "SELECT bs.seat_id, s.seat_row, s.seat_number
                  FROM booking_seats bs
                  JOIN seats s ON bs.seat_id = s.id
                  WHERE bs.booking_id = :booking_id
                  ORDER BY s.seat_row, s.seat_number";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isSeatAvailable($sessionId, $seatId)
    {
        $query = "SELECT COUNT(*) as count FROM booking_seats bs
                  JOIN bookings b ON bs.booking_id = b.id
                  WHERE bs.session_id = :session_id 
                  AND bs.seat_id = :seat_id
                  AND b.status != 'cancelled'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
        $stmt->bindParam(':seat_id', $seatId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }

    public function cancel($bookingId)
    {
        $query = "UPDATE {$this->table} SET status = 'cancelled' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($bookingId)
    {
        $deleteSeatsQuery = "DELETE FROM booking_seats WHERE booking_id = :booking_id";
        $stmtSeats = $this->conn->prepare($deleteSeatsQuery);
        $stmtSeats->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        $stmtSeats->execute();

        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getAll($limit = null, $offset = 0)
    {
        $query = "SELECT b.*, u.name as user_name, u.email as user_email,
                         m.title as movie_title, s.starts_at, r.name as room_name
                  FROM {$this->table} b
                  JOIN users u ON b.user_id = u.id
                  JOIN sessions s ON b.session_id = s.id
                  JOIN movies m ON s.movie_id = m.id
                  JOIN rooms r ON s.room_id = r.id
                  ORDER BY b.created_at DESC";

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

    public function countBookingSeats($sessionId)
    {
        $query = "SELECT COUNT(*) as total FROM booking_seats WHERE session_id = :session_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':session_id', $sessionId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getUserBookingCount($userId)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
