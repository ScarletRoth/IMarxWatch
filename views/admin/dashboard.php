<?php
SessionManager::init();
SessionManager::requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IMarxWatch</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            color: #e4e6eb;
        }
        header {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-bottom: 1px solid #374151;
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #f59e0b;
        }
        .user-info {
            color: #d1d5db;
            font-size: 14px;
        }
        nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            gap: 10px;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        nav a {
            background: #1e2530;
            color: #d1d5db;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            border: 1px solid #374151;
            transition: all 0.3s;
        }
        nav a:hover, nav a.active {
            background: #f59e0b;
            color: white;
            border-color: #f59e0b;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        h1 {
            margin-bottom: 30px;
            color: #f59e0b;
            font-size: 32px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: #1e2530;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #f59e0b;
        }
        .stat-number {
            font-size: 32px;
            font-weight: 600;
            color: #f59e0b;
            margin: 10px 0;
        }
        .stat-label {
            color: #8e9199;
            font-size: 14px;
        }
        .sessions-section {
            background: #1e2530;
            padding: 20px;
            border-radius: 12px;
        }
        .sessions-section h2 {
            margin-bottom: 20px;
            color: #ffffff;
            border-bottom: 1px solid #374151;
            padding-bottom: 10px;
        }
        .session-item {
            background: #2a3340;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .session-info {
            flex: 1;
        }
        .session-time {
            font-weight: 600;
            color: #f59e0b;
        }
        .session-details {
            font-size: 12px;
            color: #8e9199;
            margin-top: 5px;
        }
        .back-link {
            color: #f59e0b;
            text-decoration: none;
            display: block;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">🎬 IMarxWatch Admin</div>
            <div class="user-info">
                Welcome, <?php echo html(SessionManager::getCurrentUser()['name']); ?>
                | <a href="/logout" style="color: #f59e0b; text-decoration: none;">Logout</a>
            </div>
        </div>
    </header>

    <nav>
        <a href="/admin/dashboard" class="active">Dashboard</a>
        <a href="/admin/movies">Movies</a>
        <a href="/admin/users">Users</a>
        <a href="/admin/sessions">Sessions</a>
        <a href="/admin/bookings">Bookings</a>
        <a href="/">Back to Site</a>
    </nav>

    <div class="container">
        <a href="/" class="back-link">← Back to Site</a>
        <h1>📊 Admin Dashboard</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Users</div>
                <div class="stat-number"><?php echo $totalUsers; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Movies</div>
                <div class="stat-number"><?php echo $totalMovies; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Upcoming Sessions</div>
                <div class="stat-number"><?php echo count($upcomingSessions); ?></div>
            </div>
        </div>

        <div class="sessions-section">
            <h2>📅 Upcoming Sessions</h2>
            <?php if (empty($upcomingSessions)): ?>
                <p style="color: #8e9199;">No upcoming sessions</p>
            <?php else: ?>
                <?php foreach ($upcomingSessions as $session): ?>
                    <div class="session-item">
                        <div class="session-info">
                            <div class="session-time">🎬 <?php echo html($session['movie_title']); ?></div>
                            <div class="session-details">
                                Room: <?php echo html($session['room_name']); ?> | 
                                Time: <?php echo date('d M H:i', strtotime($session['starts_at'])); ?> | 
                                Price: €<?php echo number_format($session['price'], 2); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
