<?php SessionManager::init(); SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Session</title>
    <style>
        body { font-family: Arial; background: #0f1419; color: #e4e6eb; padding: 20px; max-width: 600px; margin: 0 auto; }
        form { background: #1e2530; padding: 20px; border-radius: 8px; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; background: #2a3340; border: 1px solid #374151; color: #e4e6eb; border-radius: 4px; }
        button { background: #f59e0b; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        a { color: #f59e0b; text-decoration: none; margin-left: 10px; }
    </style>
</head>
<body>
    <h1>Create New Session</h1>
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
        <a href="/admin/sessions">Cancel</a>
    </form>
</body>
</html>
