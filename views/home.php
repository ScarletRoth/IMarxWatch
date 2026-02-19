<?php
SessionManager::init();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - IMarxWatch Cinema Booking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            min-height: 100vh;
            color: #e4e6eb;
        }

        header {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px 0;
            border-bottom: 1px solid #374151;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
            flex: 1;
            justify-content: center;
        }

        nav a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #f59e0b;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        button, a.btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-login {
            background: transparent;
            border: 1px solid #f59e0b;
            color: #f59e0b;
        }

        .btn-login:hover {
            background: #f59e0b;
            color: white;
        }

        .btn-signup {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(245, 158, 11, 0.3);
        }

        .hero {
            padding: 100px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 56px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ffffff 0%, #d1d5db 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 20px;
            color: #8e9199;
            margin-bottom: 40px;
        }

        .cta-button {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 15px 40px;
            font-size: 16px;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(245, 158, 11, 0.4);
        }

        .messages {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            border-radius: 8px;
        }

        .success {
            background: #047857;
            color: #a7f3d0;
        }

        .error {
            background: #dc2626;
            color: #fecaca;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">🎬 IMarxWatch</div>
                <ul>
                    <li><a href="/movies">Now Showing</a></li>
                    <?php if (SessionManager::isAuthenticated()): ?>
                        <li><a href="/user/bookings">My Bookings</a></li>
                        <li><a href="/user/profile">Profile</a></li>
                        <?php if (SessionManager::isAdmin()): ?>
                            <li><a href="/admin/dashboard">Admin Panel</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <div class="auth-buttons">
                    <?php if (SessionManager::isAuthenticated()): ?>
                        <span>Welcome, <?php echo html(SessionManager::getCurrentUser()['name']); ?></span>
                        <a href="/logout" class="btn btn-login">Logout</a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-login">Login</a>
                        <a href="/signup" class="btn btn-signup">Sign Up</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="container messages success">
                <?php echo html($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="container messages error">
                <?php echo html($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <section class="hero">
            <div class="container">
                <h1>Welcome to IMarxWatch</h1>
                <p>Book your favorite movies with ease</p>
                <a href="/movies" class="btn cta-button">Reserve Tickets Now</a>
            </div>
        </section>
    </main>

    <footer style="text-align: center; padding: 40px 20px; border-top: 1px solid #374151; color: #6b7280;">
        <p>&copy; 2026 IMarxWatch. All rights reserved.</p>
    </footer>
</body>

</html>