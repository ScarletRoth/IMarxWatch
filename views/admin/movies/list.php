<?php SessionManager::init();
SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Movies - IMarxWatch</title>
    <link rel="stylesheet" href="/css/admin-movies-list.css">
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
        <a href="/admin/movies" class="active">Movies</a>
        <a href="/admin/users">Users</a>
        <a href="/admin/sessions">Sessions</a>
        <a href="/admin/bookings">Bookings</a>
        <a href="/">Back to Site</a>
    </nav>

    <div class="container">
        <h1>🎬 Manage Movies</h1>
        <a href="/admin/movies/create" class="action-button">+ Add New Movie</a>
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
    </div>
</body>

</html>