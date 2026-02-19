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
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            color: #e4e6eb;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .back-link {
            color: #f59e0b;
            text-decoration: none;
            margin-bottom: 30px;
            display: block;
        }
        h1 {
            margin-bottom: 30px;
            color: #f59e0b;
        }
        .booking-card {
            background: #1e2530;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            border-left: 4px solid #f59e0b;
        }
        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }
        .booking-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        .booking-status {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-confirmed {
            background: #047857;
            color: #a7f3d0;
        }
        .booking-details {
            font-size: 14px;
            color: #d1d5db;
            margin-bottom: 15px;
        }
        .booking-seats {
            background: #2a3340;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .booking-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .booking-price {
            font-size: 18px;
            font-weight: 600;
            color: #f59e0b;
        }
        .cancel-btn {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
        }
        .cancel-btn:hover {
            background: #b91c1c;
        }
        .no-bookings {
            text-align: center;
            padding: 60px 20px;
            background: #1e2530;
            border-radius: 12px;
            color: #8e9199;
        }
        .nav-links {
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
        }
        .nav-links a {
            color: #f59e0b;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid #f59e0b;
            transition: all 0.3s;
        }
        .nav-links a:hover {
            background: #f59e0b;
            color: white;
        }
    </style>
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
                <a href="/movies" style="color: #f59e0b; text-decoration: none;">Browse Movies →</a>
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
                        $seatList = array_map(function($seat) {
                            return $seat['seat_row'] . $seat['seat_number'];
                        }, $booking['seats']);
                        echo html(implode(', ', $seatList));
                        ?>
                    </div>

                    <div class="booking-footer">
                        <span class="booking-price">€<?php echo number_format($booking['total_price'], 2); ?></span>
                        <?php if ($booking['status'] === 'confirmed'): ?>
                            <form method="POST" action="/user/bookings/<?php echo $booking['id']; ?>/cancel" style="display: inline;">
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
