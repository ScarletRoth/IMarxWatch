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
    <link rel="stylesheet" href="/css/user-profile.css">
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
                <?php echo html($_SESSION['success']);
                unset($_SESSION['success']); ?>
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