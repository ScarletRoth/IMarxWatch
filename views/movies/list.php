<?php
SessionManager::init();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Now Showing - IMarxWatch</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            color: #e4e6eb;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #f59e0b;
        }
        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }
        .movie-card {
            background: #1e2530;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }
        .movie-card:hover {
            transform: translateY(-8px);
        }
        .movie-poster {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
        }
        .movie-info {
            padding: 15px;
        }
        .movie-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 10px 0;
            color: #ffffff;
        }
        .movie-rating {
            font-size: 14px;
            color: #f59e0b;
            margin-bottom: 10px;
        }
        .book-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
        }
        .back-link {
            color: #f59e0b;
            text-decoration: none;
            margin-bottom: 30px;
            display: block;
        }
        .no-movies {
            text-align: center;
            padding: 60px 20px;
            color: #8e9199;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="back-link">← Back to Home</a>
        <h1>🎬 Now Showing</h1>
        
        <?php if (empty($movies)): ?>
            <div class="no-movies">
                <h2>No movies available at the moment</h2>
                <p>Please check back soon for upcoming releases</p>
            </div>
        <?php else: ?>
            <div class="movies-grid">
                <?php foreach ($movies as $movie): ?>
                    <div class="movie-card">
                        <div class="movie-poster">🎞️</div>
                        <div class="movie-info">
                            <h3 class="movie-title"><?php echo html($movie['title']); ?></h3>
                            <?php if ($movie['rating']): ?>
                                <div class="movie-rating">⭐ <?php echo html($movie['rating']); ?></div>
                            <?php endif; ?>
                            <button class="book-btn" onclick="window.location.href='/movies/<?php echo $movie['id']; ?>'">
                                View Sessions
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
