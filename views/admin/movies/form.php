<?php SessionManager::init();
SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($movie) ? 'Edit' : 'Create'; ?> Movie</title>
    <link rel="stylesheet" href="/css/admin-movies-form.css">
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
        <h1><?php echo isset($movie) ? '✏️ Edit Movie' : '🎬 Create New Movie'; ?></h1>
    <form method="POST" action="<?php echo isset($movie) ? '/admin/movies/' . $movie['id'] : '/admin/movies'; ?>">
        <div>
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo isset($movie) ? html($movie['title']) : ''; ?>" required>
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description" required><?php echo isset($movie) ? html($movie['description']) : ''; ?></textarea>
        </div>
        <div>
            <label>Duration (minutes):</label>
            <input type="number" name="duration_minutes" value="<?php echo isset($movie) ? $movie['duration_minutes'] : ''; ?>" required>
        </div>
        <div>
            <label>Rating:</label>
            <input type="text" name="rating" value="<?php echo isset($movie) ? html($movie['rating']) : ''; ?>">
        </div>
        <div>
            <label>Poster URL:</label>
            <input type="url" name="poster_url" value="<?php echo isset($movie) ? html($movie['poster_url']) : ''; ?>">
        </div>
        <button type="submit"><?php echo isset($movie) ? 'Update' : 'Create'; ?> Movie</button>
        <a href="/admin/movies" class="cancel-link">Cancel</a>
    </form>
    </div>
</body>

</html>