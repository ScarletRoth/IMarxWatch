<?php
SessionManager::init();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo html($movie['title']); ?> - IMarxWatch</title>
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/movies-detail.css">
</head>

<body>
    <header>
        <div class="container">
            <nav>
                <div class="nav-left">
                    <button class="hamburger" id="hamburger" aria-label="Menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="logo">🎬 IMarxWatch</div>
                </div>
                <ul id="navMenu">
                    <li><a href="/movies">Now Showing</a></li>
                    <?php if (SessionManager::isAuthenticated()): ?>
                        <li><a href="/user/bookings">My Bookings</a></li>
                        <li><a href="/user/profile">Profile</a></li>
                        <?php if (SessionManager::isAdmin()): ?>
                            <li><a href="/admin/dashboard">Admin Panel</a></li>
                        <?php endif; ?>
                        <li><a href="/logout" class="menu-logout">Logout</a></li>
                    <?php endif; ?>
                </ul>
                <div class="auth-buttons">
                    <?php if (SessionManager::isAuthenticated()): ?>
                    <?php else: ?>
                        <a href="/login" class="btn btn-login">Login</a>
                        <a href="/signup" class="btn btn-signup">Sign Up</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <main class="page-container">
        <a href="/movies" class="back-link">← Back to Movies</a>

        <div class="movie-header">
            <div class="movie-header-content">
                <div class="movie-poster">
                    <?php if (!empty($movie['poster_url'])): ?>
                        <img src="<?php echo html($movie['poster_url']); ?>" alt="Poster for <?php echo html($movie['title']); ?>">
                    <?php else: ?>
                        <span class="poster-fallback">🎬</span>
                    <?php endif; ?>
                </div>
                <div class="movie-details">
                    <h1 class="movie-title">🎬 <?php echo html($movie['title']); ?></h1>
                    <div class="movie-meta">
                        <span>⭐ <?php echo html($movie['rating'] ?? 'Not rated'); ?></span>
                        <span>⏱️ <?php echo html($movie['duration_minutes']); ?> min</span>
                    </div>
                    <div class="movie-description">
                        <?php echo html($movie['description']); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="sessions-section">
            <h2>Available Sessions</h2>

            <?php if (empty($sessions)): ?>
                <div class="no-sessions">
                    <p>No upcoming sessions available for this movie</p>
                </div>
            <?php else: ?>
                <?php
                $groupedSessions = [];
                foreach ($sessions as $session) {
                    $dayKey = date('Y-m-d', strtotime($session['starts_at']));
                    if (!isset($groupedSessions[$dayKey])) {
                        $groupedSessions[$dayKey] = [];
                    }
                    $groupedSessions[$dayKey][] = $session;
                }
                ?>
                <?php foreach ($groupedSessions as $dayKey => $daySessions): ?>
                    <div class="session-day">
                        <div class="session-day-header">
                            <h3><?php echo date('l, F j, Y', strtotime($dayKey)); ?></h3>
                        </div>
                        <div class="session-times">
                            <?php foreach ($daySessions as $session): ?>
                                <?php $target = SessionManager::isAuthenticated() ? "/bookings/{$session['id']}" : "/login"; ?>
                                <a class="session-slot" href="<?php echo $target; ?>">
                                    <span class="session-time"><?php echo date('H:i', strtotime($session['starts_at'])); ?></span>
                                    <span class="session-meta">Room <?php echo html($session['room_name']); ?></span>
                                    <span class="session-price">€<?php echo number_format($session['price'], 2); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 IMarxWatch. All rights reserved.</p>
    </footer>

    <script>
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('navMenu');

        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
            if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    </script>
</body>

</html>