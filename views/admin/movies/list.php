<?php SessionManager::init(); SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Movies - IMarxWatch</title>
    <style>
        body { font-family: Arial; background: #0f1419; color: #e4e6eb; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #374151; padding: 10px; text-align: left; }
        th { background: #1e2530; }
        a { color: #f59e0b; }
    </style>
</head>
<body>
    <h1>Manage Movies</h1>
    <a href="/admin/movies/create" style="background: #f59e0b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">+ Add New Movie</a>
    <table>
        <tr>
            <th>Title</th>
            <th>Duration</th>
            <th>Rating</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($movies as $movie): ?>
            <tr>
                <td><?php echo html($movie['title']); ?></td>
                <td><?php echo $movie['duration_minutes']; ?> min</td>
                <td><?php echo html($movie['rating'] ?? 'N/A'); ?></td>
                <td>
                    <a href="/admin/movies/<?php echo $movie['id']; ?>/edit">Edit</a> |
                    <a href="/admin/movies/<?php echo $movie['id']; ?>/delete" onclick="return confirm('Delete this movie?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
