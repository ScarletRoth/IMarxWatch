# IMarxWatch
Réservation de places de cinema

# Arborescense:

IMarxWatch/
├── config/
│   ├── database.php        # Connexion PDO
│   └── config.php          # Constantes (URL, paramètres de session)
│   └── init_db.php 
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

**3. Initialiser la base de données**
```powershell
cd "C:\Users\Stpn_Angelo\Documents\Ynov\YTrack\PHP\todo-app"
php init_db.php
```

**4. Démarrer le serveur PHP**
```powershell
php -S localhost:8000 -t public
```

**5. Ouvrir dans le navigateur**
```
http://localhost:8000
```