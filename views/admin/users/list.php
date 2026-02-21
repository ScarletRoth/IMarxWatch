<?php SessionManager::init();
SessionManager::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - IMarxWatch</title>
    <link rel="stylesheet" href="/css/admin-users-list.css">
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
        <a href="/admin/users" class="active">Users</a>
        <a href="/admin/sessions">Sessions</a>
        <a href="/admin/bookings">Bookings</a>
        <a href="/">Back to Site</a>
    </nav>

    <div class="container">
        <h1>👥 Manage Users</h1>
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
    </div>
</body>

</html>