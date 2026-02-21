<?php SessionManager::init();
SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Session</title>
    <link rel="stylesheet" href="/css/admin-sessions-form.css">
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
        <h1>📅 Create New Session</h1>
    <form method="POST" action="/admin/sessions">
        <div>
            <label>Movie:</label>
            <select name="movie_id" required>
                <option value="">Select a movie...</option>
                <?php foreach ($movies as $m): ?>
                    <option value="<?php echo $m['id']; ?>"><?php echo html($m['title']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Room:</label>
            <select name="room_id" required>
                <option value="">Select a room...</option>
                <?php foreach ($rooms as $r): ?>
                    <option value="<?php echo $r['id']; ?>"><?php echo html($r['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Start Time:</label>
            <input type="datetime-local" name="starts_at" required>
        </div>
        <div>
            <label>Price (€):</label>
            <input type="number" step="0.01" name="price" required>
        </div>
        <button type="submit">Create Session</button>
        <a href="/admin/sessions" class="cancel-link">Cancel</a>
    </form>
    </div>
</body>

</html>