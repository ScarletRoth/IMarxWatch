<?php SessionManager::init(); SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - IMarxWatch</title>
    <style>
        body { font-family: Arial; background: #0f1419; color: #e4e6eb; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #374151; padding: 10px; text-align: left; }
        th { background: #1e2530; }
        a { color: #f59e0b; }
    </style>
</head>
<body>
    <h1>Manage Users</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo html($user['name']); ?></td>
                <td><?php echo html($user['email']); ?></td>
                <td><?php echo ucfirst($user['role']); ?></td>
                <td>
                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <a href="/admin/users/<?php echo $user['id']; ?>/delete" onclick="return confirm('Delete this user?')">Delete</a>
                    <?php else: ?>
                        <em>(Your account)</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
