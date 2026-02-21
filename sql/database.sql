CREATE DATABASE IF NOT EXISTS imarxwatch
	CHARACTER SET utf8mb4
	COLLATE utf8mb4_unicode_ci;

USE imarxwatch;

CREATE TABLE IF NOT EXISTS users (
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL,
	email VARCHAR(150) NOT NULL UNIQUE,
	password_hash VARCHAR(255) NOT NULL,
	role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS movies (
	id INT PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(200) NOT NULL,
	description TEXT,
	duration_minutes INT NOT NULL,
	rating VARCHAR(20),
	poster_url VARCHAR(255),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS genres (
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS movie_genres (
	movie_id INT NOT NULL,
	genre_id INT NOT NULL,
	PRIMARY KEY (movie_id, genre_id),
	CONSTRAINT fk_movie_genres_movie
		FOREIGN KEY (movie_id) REFERENCES movies(id)
		ON DELETE CASCADE,
	CONSTRAINT fk_movie_genres_genre
		FOREIGN KEY (genre_id) REFERENCES genres(id)
		ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS rooms (
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	seats_total INT NOT NULL,
	UNIQUE KEY uniq_room_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS seats (
	id INT PRIMARY KEY AUTO_INCREMENT,
	room_id INT NOT NULL,
	seat_row VARCHAR(5) NOT NULL,
	seat_number INT NOT NULL,
	CONSTRAINT fk_seats_room
		FOREIGN KEY (room_id) REFERENCES rooms(id)
		ON DELETE CASCADE,
	UNIQUE KEY uniq_room_seat (room_id, seat_row, seat_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sessions (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS bookings (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS booking_seats (
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
		FOREIGN KEY (seat_id) REFERENCES seats(id)
		ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payments (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS remember_tokens (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO movies (title, description, duration_minutes, rating, poster_url)
VALUES
	('Wuthering Heights', 'A passionate and tumultuous love story set against the backdrop of the Yorkshire moors, exploring the intense and destructive relationship between Heathcliff and Catherine Earnshaw.', 136, 'R', '/images/wuthering-heights.png'),
	('Crime 101', 'An elusive thief, eyeing his final score, encounters a disillusioned insurance broker at her own crossroads. As their paths intertwine, a relentless detective trails them hoping to thwart the multi-million dollar heist they are planning.', 140, 'R', '/images/crime-101.png'),
	('Send Help', 'An employee and her insufferable boss become stranded on a deserted island, the only survivors of a plane crash. Here, they must overcome past grievances and work together to make it out alive.', 113, 'R', '/images/send-help.png'),
	('GOAT', 'A small goat with big dreams gets a once-in-a-lifetime shot to join the pros and play roarball, a high-intensity, co-ed, full-contact sport dominated by the fastest, fiercest animals in the world.', 100, 'PG', '/images/goat.png'),
	('O'' Romeo', 'In post-independence Mumbai, the underworld rises amidst a changing city. This gritty tale explores the criminal landscape of a bygone era, weaving through the streets and shadows of India''s bustling metropolis.', 178, '18', '/images/o-romeo.png'),
	('The Wrecking Crew', 'Estranged half-brothers Jonny and James reunite after their father''s mysterious death. As they search for the truth, buried secrets reveal a conspiracy threatening to tear their family apart.', 124, '15', '/images/the-wrecking-crew.png'),
	('Iron Lung', 'In a post-apocalyptic future after "The Quiet Rapture" event, a convict explores a blood ocean on a desolate moon using a submarine called the "Iron Lung" to search for missing stars/planets.', 125, '15', '/images/iron-lung.png'),
	('The Rip', 'A group of Miami cops discovers a stash of millions in cash, leading to distrust as outsiders learn about the huge seizure, making them question who to rely on.', 113, '15', '/images/the-rip.png'),
	('Love Me, Love Me', 'New at Milan''s Saint Mary''s, June is swept into secrets and lies: no one is who they seem, and love may hide behind the mask of the last boy she''d fall for.', 99, NULL, '/images/love-me-love-me.png'),
	('Melania', 'An intimate chronicle offers a rare glimpse into the life of Melania Trump, exploring her role as First Lady and her relationship with the President.', 104, 'PG', '/images/melania.png');
