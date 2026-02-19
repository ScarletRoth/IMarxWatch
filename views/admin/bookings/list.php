<?php SessionManager::init(); SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings - IMarxWatch</title>
    <style>
        body { font-family: Arial; background: #0f1419; color: #e4e6eb; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #374151; padding: 10px; text-align: left; }
        th { background: #1e2530; }
        a { color: #f59e0b; }
    </style>
</head>
<body>
    <h1>All Bookings</h1>
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
</body>
</html>
