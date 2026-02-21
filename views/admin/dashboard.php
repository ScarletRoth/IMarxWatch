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
    <link rel="stylesheet" href="/css/admin-dashboard.css">
</head>

<body>
    <header>
        <div class="header-content">
            <div class="logo">🎬 IMarxWatch Admin</div>
            <div class="user-info">
                <a href="/logout" class="logout-link">Logout</a>
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
                <p class="empty-text">No upcoming sessions</p>
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