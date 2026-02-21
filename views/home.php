<?php
SessionManager::init();

require_once __DIR__ . '/../models/Movie.php';

$nowShowing = [];
try {
    $db = (new Database())->getConnection();
    $movieModel = new Movie($db);
    $nowShowing = $movieModel->getAll(8, 0);
} catch (Exception $e) {
    $nowShowing = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - IMarxWatch Cinema Booking</title>
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/home.css">
</head>

<body>
    <header>
        <div class="header-container">
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

    <script>
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('navMenu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });

        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    </script>

    <main>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="container messages success">
                <?php echo html($_SESSION['success']);
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="container messages error">
                <?php echo html($_SESSION['error']);
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <section class="hero">
            <div class="container">
                <h1>Welcome to IMarxWatch</h1>
                <p>Book your favorite movies with ease</p>
                <a href="/movies" class="btn cta-button">Reserve Tickets Now</a>
            </div>
        </section>

        <section class="now-showing">
            <div class="container">
                <div class="section-header">
                    <div>
                        <p class="section-eyebrow">Now Showing</p>
                        <h2>Fresh on the big screen</h2>
                    </div>
                    <a href="/movies" class="btn ghost-button">View all movies</a>
                </div>

                <?php if (empty($nowShowing)): ?>
                    <div class="empty-state">No movies available right now. Please check back soon.</div>
                <?php else: ?>
                    <div class="now-showing-grid">
                        <?php foreach ($nowShowing as $movie): ?>
                            <article class="now-showing-card">
                                <div class="now-showing-poster">
                                    <?php if (!empty($movie['poster_url'])): ?>
                                        <img src="<?php echo html($movie['poster_url']); ?>" alt="Poster for <?php echo html($movie['title']); ?>">
                                    <?php else: ?>
                                        <span>🎬</span>
                                    <?php endif; ?>
                                </div>
                                <div class="now-showing-info">
                                    <h3><?php echo html($movie['title']); ?></h3>
                                    <p><?php echo html($movie['rating'] ?? 'Not rated'); ?> • <?php echo html($movie['duration_minutes']); ?> min</p>
                                    <a class="card-link" href="/movies/<?php echo $movie['id']; ?>">See sessions</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 IMarxWatch. All rights reserved.</p>
    </footer>
</body>

</html>