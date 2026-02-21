<?php
SessionManager::init();
SessionManager::requireAuth();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - IMarxWatch</title>
    <link rel="stylesheet" href="/css/user-bookings.css">
</head>

<body>
    <div class="container">
        <a href="/" class="back-link">← Back to Home</a>

        <div class="nav-links">
            <a href="/user/profile">Profile</a>
            <a href="/movies">Browse Movies</a>
        </div>

        <h1>📋 My Bookings</h1>

        <?php if (empty($bookings)): ?>
            <div class="no-bookings">
                <h2>No bookings yet</h2>
                <p>Start booking your favorite movies now!</p>
                <a href="/movies" class="browse-link">Browse Movies →</a>
            </div>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <h3 class="booking-title">🎬 <?php echo html($booking['movie_title']); ?></h3>
                        <span class="booking-status status-<?php echo $booking['status']; ?>">
                            <?php echo strtoupper($booking['status']); ?>
                        </span>
                    </div>

                    <div class="booking-details">
                        <div>📍 <?php echo html($booking['room_name']); ?></div>
                        <div>🕒 <?php echo date('d M Y H:i', strtotime($booking['starts_at'])); ?></div>
                        <div><?php echo $booking['seats_count']; ?> seat(s)</div>
                    </div>

                    <div class="booking-seats">
                        <strong>Seats:</strong>
                        <?php
                        $seatList = array_map(function ($seat) {
                            return $seat['seat_row'] . $seat['seat_number'];
                        }, $booking['seats']);
                        echo html(implode(', ', $seatList));
                        ?>
                    </div>

                    <div class="booking-footer">
                        <span class="booking-price">€<?php echo number_format($booking['total_price'], 2); ?></span>
                        <?php if ($booking['status'] === 'confirmed'): ?>
                            <form method="POST" action="/user/bookings/<?php echo $booking['id']; ?>/cancel" class="inline-form">
                                <button type="submit" class="cancel-btn" onclick="return confirm('Are you sure?')">
                                    Cancel Booking
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>