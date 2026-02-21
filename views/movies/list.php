<?php
SessionManager::init();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Now Showing - IMarxWatch</title>
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/movies-list.css">
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

    <main class="page-container">
        <a href="/" class="back-link">← Back to Home</a>
        <h1>🎬 Now Showing</h1>

        <?php if (empty($movies)): ?>
            <div class="no-movies">
                <h2>No movies available at the moment</h2>
                <p>Please check back soon for upcoming releases</p>
            </div>
        <?php else: ?>
            <div class="movies-grid">
                <?php foreach ($movies as $movie): ?>
                    <div class="movie-card">
                        <div class="movie-poster">
                            <?php if (!empty($movie['poster_url'])): ?>
                                <img src="<?php echo html($movie['poster_url']); ?>" alt="Poster for <?php echo html($movie['title']); ?>">
                            <?php else: ?>
                                <span class="poster-fallback">🎞️</span>
                            <?php endif; ?>
                        </div>
                        <div class="movie-info">
                            <h3 class="movie-title"><?php echo html($movie['title']); ?></h3>
                            <?php if ($movie['rating']): ?>
                                <div class="movie-rating">⭐ <?php echo html($movie['rating']); ?></div>
                            <?php endif; ?>
                            <button class="book-btn" onclick="window.location.href='/movies/<?php echo $movie['id']; ?>'">
                                View Sessions
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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