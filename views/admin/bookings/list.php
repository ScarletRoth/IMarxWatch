<?php SessionManager::init();
SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - IMarxWatch</title>
    <link rel="stylesheet" href="/css/admin-bookings-list.css">
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
        <a href="/admin/dashboard">Dashboard</a>
        <a href="/admin/movies">Movies</a>
        <a href="/admin/users">Users</a>
        <a href="/admin/sessions">Sessions</a>
        <a href="/admin/bookings" class="active">Bookings</a>
        <a href="/">Back to Site</a>
    </nav>

    <div class="container">
        <h1>📋 All Bookings</h1>
        <table>
        <tr>
            <th>User</th>
            <th>Movie</th>
            <th>Seats</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo html($booking['user_name']); ?></td>
                <td><?php echo html($booking['movie_title']); ?></td>
                <td><?php echo $booking['seats_count']; ?></td>
                <td>€<?php echo number_format($booking['total_price'], 2); ?></td>
                <td><?php echo ucfirst($booking['status']); ?></td>
                <td>
                    <a href="/admin/bookings/<?php echo $booking['id']; ?>/delete" onclick="return confirm('Delete this booking?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>
</body>

</html>