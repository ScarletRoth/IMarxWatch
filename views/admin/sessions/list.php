<?php SessionManager::init();
SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sessions - IMarxWatch</title>
    <link rel="stylesheet" href="/css/admin-sessions-list.css">
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
        <a href="/admin/sessions" class="active">Sessions</a>
        <a href="/admin/bookings">Bookings</a>
        <a href="/">Back to Site</a>
    </nav>

    <div class="container">
        <h1>📅 Manage Sessions</h1>
        <a href="/admin/sessions/create" class="action-button">+ Add New Session</a>
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
    </div>
</body>

</html>