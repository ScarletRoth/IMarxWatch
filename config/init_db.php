<?php

try {
    $dbName = 'imarxwatch';
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Base de données '{$dbName}' créée ou déjà existante.\n";

    $pdo->exec("USE {$dbName}");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS movies (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        duration_minutes INT NOT NULL,
        rating VARCHAR(20),
        poster_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS genres (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(80) NOT NULL UNIQUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS movie_genres (
        movie_id INT NOT NULL,
        genre_id INT NOT NULL,
        PRIMARY KEY (movie_id, genre_id),
        CONSTRAINT fk_movie_genres_movie
            FOREIGN KEY (movie_id) REFERENCES movies(id)
            ON DELETE CASCADE,
        CONSTRAINT fk_movie_genres_genre
            FOREIGN KEY (genre_id) REFERENCES genres(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS rooms (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        seats_total INT NOT NULL,
        UNIQUE KEY uniq_room_name (name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS seats (
        id INT PRIMARY KEY AUTO_INCREMENT,
        room_id INT NOT NULL,
        seat_row VARCHAR(5) NOT NULL,
        seat_number INT NOT NULL,
        CONSTRAINT fk_seats_room
            FOREIGN KEY (room_id) REFERENCES rooms(id)
            ON DELETE CASCADE,
        UNIQUE KEY uniq_room_seat (room_id, seat_row, seat_number)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS sessions (
        id INT PRIMARY KEY AUTO_INCREMENT,
        movie_id INT NOT NULL,
        room_id INT NOT NULL,
        starts_at DATETIME NOT NULL,
        price DECIMAL(8,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_sessions_movie
            FOREIGN KEY (movie_id) REFERENCES movies(id)
            ON DELETE CASCADE,
        CONSTRAINT fk_sessions_room
            FOREIGN KEY (room_id) REFERENCES rooms(id)
            ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        session_id INT NOT NULL,
        seats_count INT NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_bookings_user
            FOREIGN KEY (user_id) REFERENCES users(id)
            ON DELETE CASCADE,
        CONSTRAINT fk_bookings_session
            FOREIGN KEY (session_id) REFERENCES sessions(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS booking_seats (
        booking_id INT NOT NULL,
        session_id INT NOT NULL,
        seat_id INT NOT NULL,
        PRIMARY KEY (booking_id, seat_id),
        UNIQUE KEY uniq_session_seat (session_id, seat_id),
        CONSTRAINT fk_booking_seats_booking
            FOREIGN KEY (booking_id) REFERENCES bookings(id)
            ON DELETE CASCADE,
        CONSTRAINT fk_booking_seats_session
            FOREIGN KEY (session_id) REFERENCES sessions(id)
            ON DELETE CASCADE,
        CONSTRAINT fk_booking_seats_seat
            FOREIGN KEY (seat_id) REFERENCES seats(id)
            ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS payments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        booking_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        method ENUM('card', 'cash', 'paypal') NOT NULL,
        status ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
        transaction_ref VARCHAR(100),
        paid_at DATETIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_payments_booking
            FOREIGN KEY (booking_id) REFERENCES bookings(id)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS remember_tokens (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        token_hash VARCHAR(255) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_remember_tokens_user
            FOREIGN KEY (user_id) REFERENCES users(id)
            ON DELETE CASCADE,
        INDEX idx_token_hash (token_hash),
        INDEX idx_expires_at (expires_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    echo "✓ Tables 'users', 'movies', 'genres', 'movie_genres', 'rooms', 'seats', 'sessions', 'bookings', 'booking_seats', 'payments', 'remember_tokens' creees ou deja existantes.\n";

    echo "\n✓ Base de données initialisée avec succès !\n";
} catch (PDOException $e) {
    echo "✕ Erreur : " . $e->getMessage() . "\n";
}
