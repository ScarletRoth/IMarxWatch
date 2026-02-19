<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/SessionManager.php';
require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../models/Session.php';

class MovieController
{
    private $db;
    private $movieModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->movieModel = new Movie($this->db);
    }

    public function listMovies()
    {
        try {
            $movies = $this->movieModel->getMoviesWithUpcomingSessions();
            include VIEWS_PATH . '/movies/list.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading movies: ' . $e->getMessage();
            header('Location: /');
            exit();
        }
    }

    public function detail($movieId)
    {
        try {
            $movie = $this->movieModel->getById($movieId);
            
            if (!$movie) {
                http_response_code(404);
                $_SESSION['error'] = 'Movie not found';
                header('Location: /movies');
                exit();
            }

            $sessionModel = new SessionModel($this->db);
            $sessions = $sessionModel->getSessionsByMovieId($movieId, true);

            include VIEWS_PATH . '/movies/detail.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error loading movie details: ' . $e->getMessage();
            header('Location: /movies');
            exit();
        }
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header('Location: /admin/movies');
            exit();
        }

        include VIEWS_PATH . '/admin/movie_form.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/movies');
            exit();
        }

        try {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $duration = (int)($_POST['duration_minutes'] ?? 0);
            $rating = trim($_POST['rating'] ?? '');
            $posterUrl = trim($_POST['poster_url'] ?? '');

            if (empty($title) || empty($description) || $duration <= 0) {
                $_SESSION['error'] = 'Please fill in all required fields';
                header('Location: /admin/movies/create');
                exit();
            }

            if ($this->movieModel->create($title, $description, $duration, $rating ?: null, $posterUrl ?: null)) {
                $_SESSION['success'] = 'Movie created successfully';
                header('Location: /admin/movies');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to create movie';
                header('Location: /admin/movies/create');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /admin/movies/create');
            exit();
        }
    }

    public function edit($movieId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header('Location: /admin/movies');
            exit();
        }

        try {
            $movie = $this->movieModel->getById($movieId);
            
            if (!$movie) {
                $_SESSION['error'] = 'Movie not found';
                header('Location: /admin/movies');
                exit();
            }

            include VIEWS_PATH . '/admin/movie_form.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /admin/movies');
            exit();
        }
    }

    public function update($movieId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/movies');
            exit();
        }

        try {
            $movie = $this->movieModel->getById($movieId);
            
            if (!$movie) {
                $_SESSION['error'] = 'Movie not found';
                header('Location: /admin/movies');
                exit();
            }

            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'duration_minutes' => (int)($_POST['duration_minutes'] ?? 0),
                'rating' => trim($_POST['rating'] ?? '') ?: null,
                'poster_url' => trim($_POST['poster_url'] ?? '') ?: null
            ];

            if (empty($data['title']) || empty($data['description']) || $data['duration_minutes'] <= 0) {
                $_SESSION['error'] = 'Please fill in all required fields';
                header('Location: /admin/movies/' . $movieId . '/edit');
                exit();
            }

            if ($this->movieModel->update($movieId, $data)) {
                $_SESSION['success'] = 'Movie updated successfully';
                header('Location: /admin/movies');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to update movie';
                header('Location: /admin/movies/' . $movieId . '/edit');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /admin/movies');
            exit();
        }
    }

    public function delete($movieId)
    {
        try {
            $movie = $this->movieModel->getById($movieId);
            
            if (!$movie) {
                $_SESSION['error'] = 'Movie not found';
                header('Location: /admin/movies');
                exit();
            }

            if ($this->movieModel->delete($movieId)) {
                $_SESSION['success'] = 'Movie deleted successfully';
            } else {
                $_SESSION['error'] = 'Failed to delete movie';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: /admin/movies');
        exit();
    }
}
