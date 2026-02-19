<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/SessionManager.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Session.php';

class BookingController
{
    private $db;
    private $bookingModel;
    private $sessionModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->bookingModel = new Booking($this->db);
        $this->sessionModel = new SessionModel($this->db);
    }

    public function show($sessionId)
    {
        SessionManager::init();
        SessionManager::requireAuth();

        try {
            $session = $this->sessionModel->getById($sessionId);
            
            if (!$session || $session['starts_at'] < date('Y-m-d H:i:s')) {
                $_SESSION['error'] = 'Session not found or already started';
                header('Location: /movies');
                exit();
            }

            $allSeats = $this->sessionModel->getAllSeats($sessionId);
            $bookedSeats = $this->sessionModel->getBookedSeats($sessionId);
            $bookedSeatIds = array_column($bookedSeats, 'seat_id');

            include VIEWS_PATH . '/bookings/select.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /movies');
            exit();
        }
    }

    public function store()
    {
        SessionManager::init();
        SessionManager::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /bookings');
            exit();
        }

        try {
            $sessionId = (int)$_POST['session_id'] ?? 0;
            $selectedSeats = $_POST['seats'] ?? [];

            if (!$sessionId || empty($selectedSeats)) {
                $_SESSION['error'] = 'Please select at least one seat';
                header('Location: /bookings/' . $sessionId);
                exit();
            }

            $session = $this->sessionModel->getById($sessionId);
            if (!$session || $session['starts_at'] < date('Y-m-d H:i:s')) {
                $_SESSION['error'] = 'Session not available';
                header('Location: /movies');
                exit();
            }

            foreach ($selectedSeats as $seatId) {
                if (!$this->bookingModel->isSeatAvailable($sessionId, (int)$seatId)) {
                    $_SESSION['error'] = 'One or more seats are no longer available';
                    header('Location: /bookings/' . $sessionId);
                    exit();
                }
            }

            $userId = $_SESSION['user_id'];
            $seatsCount = count($selectedSeats);
            $totalPrice = $seatsCount * $session['price'];

            if (!$this->bookingModel->create($userId, $sessionId, $seatsCount, $totalPrice)) {
                $_SESSION['error'] = 'Failed to create booking';
                header('Location: /bookings/' . $sessionId);
                exit();
            }

            $bookingId = $this->bookingModel->getLastInsertId();

            foreach ($selectedSeats as $seatId) {
                $this->bookingModel->addSeat($bookingId, $sessionId, (int)$seatId);
            }

            $_SESSION['success'] = 'Booking confirmed! Total: €' . number_format($totalPrice, 2);
            header('Location: /user/bookings');
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /movies');
            exit();
        }
    }

    public function userBookings()
    {
        SessionManager::init();
        SessionManager::requireAuth();

        try {
            $userId = $_SESSION['user_id'];
            $bookings = $this->bookingModel->getUserBookings($userId);

            foreach ($bookings as &$booking) {
                $booking['seats'] = $this->bookingModel->getBookingSeats($booking['id']);
            }

            include VIEWS_PATH . '/user/bookings.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading bookings: ' . $e->getMessage();
            header('Location: /');
            exit();
        }
    }

    public function cancel($bookingId)
    {
        SessionManager::init();
        SessionManager::requireAuth();

        try {
            $booking = $this->bookingModel->getById($bookingId);
            
            if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'Booking not found or unauthorized';
                header('Location: /user/bookings');
                exit();
            }

            if ($this->bookingModel->cancel($bookingId)) {
                $_SESSION['success'] = 'Booking cancelled successfully';
            } else {
                $_SESSION['error'] = 'Failed to cancel booking';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: /user/bookings');
        exit();
    }

    public function adminList()
    {
        SessionManager::init();
        SessionManager::requireAdmin();

        try {
            $bookings = $this->bookingModel->getAll();
            include VIEWS_PATH . '/admin/bookings/list.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /admin/dashboard');
            exit();
        }
    }

    public function adminDelete($bookingId)
    {
        SessionManager::init();
        SessionManager::requireAdmin();

        try {
            if ($this->bookingModel->delete($bookingId)) {
                $_SESSION['success'] = 'Booking deleted successfully';
            } else {
                $_SESSION['error'] = 'Failed to delete booking';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: /admin/bookings');
        exit();
    }
}
