<?php
SessionManager::init();
SessionManager::requireAuth();
$user = SessionManager::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - IMarxWatch</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            color: #e4e6eb;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
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
        .profile-card {
            background: #1e2530;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #d1d5db;
            font-weight: 500;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            background: #2a3340;
            border: 1px solid #374151;
            border-radius: 6px;
            color: #e4e6eb;
            font-size: 14px;
            box-sizing: border-box;
        }
        input:focus {
            outline: none;
            border-color: #f59e0b;
        }
        button {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .danger-btn {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            margin-top: 20px;
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
        }
        .danger-zone {
            background: #7f1d1d;
            border: 1px solid #b91c1c;
            padding: 20px;
            border-radius: 12px;
            margin-top: 30px;
        }
        .danger-zone h3 {
            color: #fca5a5;
            margin-top: 0;
        }
        .info-box {
            background: #065f46;
            border: 1px solid #047857;
            color: #a7f3d0;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="back-link">← Back to Home</a>
        
        <div class="nav-links">
            <a href="/user/bookings">My Bookings</a>
            <a href="/movies">Browse Movies</a>
        </div>

        <h1>👤 My Profile</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="info-box success">
                <?php echo html($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="profile-card">
            <form method="POST" action="/user/profile/update">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo html($user['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo html($user['email']); ?>" required>
                </div>

                <button type="submit">Update Profile</button>
            </form>
        </div>

        <div class="danger-zone">
            <h3>⚠️ Danger Zone</h3>
            <p>Once you delete your account, there is no going back. Please be certain.</p>
            <form method="POST" action="/user/profile/delete" onsubmit="return confirm('Are you absolutely sure? This action cannot be undone.');">
                <div class="form-group">
                    <label for="password">Confirm Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password to delete account" required>
                </div>
                <button type="submit" class="danger-btn">Delete My Account</button>
            </form>
        </div>
    </div>
</body>
</html>
