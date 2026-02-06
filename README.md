# IMarxWatch
Réservation de places de cinema

# Arborescense:

IMarxWatch/
├── config/
│   ├── database.php        # Connexion PDO
│   └── config.php          # Constantes (URL, paramètres de session)
├── controllers/
│   ├── AuthController.php  # Login, Register, Logout
│   ├── MovieController.php # Affichage des films et séances
│   ├── BookingController.php # Gestion des réservations
│   └── AdminController.php # Gestion Admin (Films, Users)
├── models/
│   ├── User.php            # Requêtes SQL table 'users'
│   ├── Movie.php           # Requêtes SQL table 'movies'
│   ├── Session.php         # Requêtes SQL table 'sessions'
│   └── Booking.php         # Requêtes SQL table 'bookings'
├── views/
│   ├── auth/               # Forms login/register
│   ├── movies/             # Liste des films, détails séance
│   ├── user/               # Profil, mes réservations
│   ├── admin/              # Dashboard, gestion films/users
│   ├── layout/             # Header, Footer (réutilisables)
│   └── home.php            # Page d'accueil
├── public/                 # Seul dossier accessible au public
│   ├── css/
│   ├── js/
│   └── index.php           # LE Routeur (Point d'entrée unique)
├── sql/
│   └── database.sql        # Script de création des tables
├── .htaccess               # Pour rediriger toutes les requêtes vers index.php
└── README.md