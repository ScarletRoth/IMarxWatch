<?php
SessionManager::init();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo html($movie['title']); ?> - IMarxWatch</title>
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
        .movie-header {
            background: #1e2530;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 40px;
        }
        .movie-title {
            font-size: 32px;
            margin: 0 0 10px 0;
            color: #ffffff;
        }
        .movie-meta {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            font-size: 14px;
            color: #8e9199;
        }
        .movie-description {
            margin: 20px 0;
            line-height: 1.6;
            color: #d1d5db;
        }
        .sessions-section h2 {
            margin-bottom: 20px;
            color: #f59e0b;
        }
        .sessions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        .session-card {
            background: #1e2530;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .session-time {
            font-size: 18px;
            font-weight: 600;
            margin: 10px 0;
        }
        .session-room {
            font-size: 12px;
            color: #8e9199;
            margin: 10px 0;
        }
        .session-price {
            font-size: 16px;
            color: #f59e0b;
            font-weight: 600;
            margin: 10px 0;
        }
        .book-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            margin-top: 10px;
        }
        .no-sessions {
            text-align: center;
            padding: 40px;
            color: #8e9199;
            background: #1e2530;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/movies" class="back-link">← Back to Movies</a>
        
        <div class="movie-header">
            <h1 class="movie-title">🎬 <?php echo html($movie['title']); ?></h1>
            <div class="movie-meta">
                <span>⭐ <?php echo html($movie['rating'] ?? 'Not rated'); ?></span>
                <span>⏱️ <?php echo html($movie['duration_minutes']); ?> min</span>
            </div>
            <div class="movie-description">
                <?php echo html($movie['description']); ?>
            </div>
        </div>

        <div class="sessions-section">
            <h2>Available Sessions</h2>
            
            <?php if (empty($sessions)): ?>
                <div class="no-sessions">
                    <p>No upcoming sessions available for this movie</p>
                </div>
            <?php else: ?>
                <div class="sessions-grid">
                    <?php foreach ($sessions as $session): ?>
                        <div class="session-card">
                            <div class="session-time"><?php echo date('H:i', strtotime($session['starts_at'])); ?></div>
                            <div class="session-date"><?php echo date('d M', strtotime($session['starts_at'])); ?></div>
                            <div class="session-room">Room: <?php echo html($session['room_name']); ?></div>
                            <div class="session-price">€<?php echo number_format($session['price'], 2); ?></div>
                            
                            <?php if (SessionManager::isAuthenticated()): ?>
                                <button class="book-btn" onclick="window.location.href='/bookings/<?php echo $session['id']; ?>'">
                                    Book Seats
                                </button>
                            <?php else: ?>
                                <button class="book-btn" onclick="window.location.href='/login'">
                                    Login to Book
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
