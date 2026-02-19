<?php SessionManager::init(); SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sessions - IMarxWatch</title>
    <style>
        body { font-family: Arial; background: #0f1419; color: #e4e6eb; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #374151; padding: 10px; text-align: left; }
        th { background: #1e2530; }
        a { color: #f59e0b; }
    </style>
</head>
<body>
    <h1>Manage Sessions</h1>
    <a href="/admin/sessions/create" style="background: #f59e0b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">+ Add New Session</a>
    <table>
        <tr>
            <th>Movie</th>
            <th>Room</th>
            <th>Start Time</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($sessions as $session): ?>
            <tr>
                <td><?php echo html($session['movie_title']); ?></td>
                <td><?php echo html($session['room_name']); ?></td>
                <td><?php echo date('d M Y H:i', strtotime($session['starts_at'])); ?></td>
                <td>€<?php echo number_format($session['price'], 2); ?></td>
                <td>
                    <a href="/admin/sessions/<?php echo $session['id']; ?>/delete" onclick="return confirm('Delete this session?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
