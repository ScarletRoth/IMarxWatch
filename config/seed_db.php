<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=imarxwatch', 'matteo', 'matteo1711');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $adminEmail = 'admin@imarxwatch.com';
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$adminEmail]);
    
    if (!$stmt->fetch()) {
        $adminPassword = password_hash('Admin@123', PASSWORD_ARGON2ID);
        $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)')
            ->execute(['Admin User', $adminEmail, $adminPassword, 'admin']);
        echo "✓ Admin user created: admin@imarxwatch.com / Admin@123\n";
    } else {
        echo "✓ Admin user already exists\n";
    }

    $stmt = $pdo->prepare('SELECT id FROM rooms WHERE name = ?');
    $stmt->execute(['Salle 1']);
    $roomResult = $stmt->fetch();
    
    if (!$roomResult) {
        $pdo->prepare('INSERT INTO rooms (name, seats_total) VALUES (?, ?)')
            ->execute(['Salle 1', 100]);
        $roomId = $pdo->lastInsertId();
        echo "✓ Room created: Salle 1\n";
        
        for ($row = 'A'; $row <= 'J'; $row++) {
            for ($seat = 1; $seat <= 10; $seat++) {
                $pdo->prepare('INSERT INTO seats (room_id, seat_row, seat_number) VALUES (?, ?, ?)')
                    ->execute([$roomId, $row, $seat]);
            }
        }
        echo "✓ 100 seats created in room\n";
    } else {
        $roomId = $roomResult['id'];
        echo "✓ Room already exists\n";
    }

    $stmt = $pdo->prepare('SELECT id FROM movies WHERE title = ?');
    $stmt->execute(['Inception']);
    $movieResult = $stmt->fetch();
    
    if (!$movieResult) {
        $pdo->prepare('INSERT INTO movies (title, description, duration_minutes, rating, poster_url) VALUES (?, ?, ?, ?, ?)')
            ->execute([
                'Inception',
                'A skilled thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
                148,
                'PG-13',
                'https://via.placeholder.com/300x450?text=Inception'
            ]);
        $movieId = $pdo->lastInsertId();
        echo "✓ Movie created: Inception\n";
    } else {
        $movieId = $movieResult['id'];
        echo "✓ Movie already exists\n";
    }

    $stmt = $pdo->prepare('SELECT id FROM sessions WHERE movie_id = ? AND room_id = ?');
    $stmt->execute([$movieId, $roomId]);
    $sessionsCount = $stmt->rowCount();
    
    if ($sessionsCount == 0) {
        for ($day = 0; $day < 2; $day++) {
            $baseDate = date('Y-m-d', strtotime("+$day days"));
            
            foreach ([14, 17, 20] as $hour) {
                $startTime = $baseDate . ' ' . sprintf('%02d', $hour) . ':00:00';
                $price = 12.50;
                
                $pdo->prepare('INSERT INTO sessions (movie_id, room_id, starts_at, price) VALUES (?, ?, ?, ?)')
                    ->execute([$movieId, $roomId, $startTime, $price]);
            }
        }
        echo "✓ 6 sessions created for the movie\n";
    } else {
        echo "✓ Sessions already exist\n";
    }

    echo "\n✓ Database seeded successfully!\n";
    echo "\nTest credentials:\n";
    echo "• Email: admin@imarxwatch.com\n";
    echo "• Password: Admin@123\n";
    echo "• Role: admin\n";

} catch (PDOException $e) {
    echo "✕ Error: " . $e->getMessage() . "\n";
    exit(1);
}
