<?php SessionManager::init(); SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($movie) ? 'Edit' : 'Create'; ?> Movie</title>
    <style>
        body { font-family: Arial; background: #0f1419; color: #e4e6eb; padding: 20px; max-width: 600px; margin: 0 auto; }
        form { background: #1e2530; padding: 20px; border-radius: 8px; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; background: #2a3340; border: 1px solid #374151; color: #e4e6eb; border-radius: 4px; }
        button { background: #f59e0b; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        a { color: #f59e0b; text-decoration: none; margin-left: 10px; }
    </style>
</head>
<body>
    <h1><?php echo isset($movie) ? 'Edit Movie' : 'Create New Movie'; ?></h1>
    <form method="POST" action="<?php echo isset($movie) ? '/admin/movies/'.$movie['id'] : '/admin/movies'; ?>">
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
        <a href="/admin/movies">Cancel</a>
    </form>
</body>
</html>
