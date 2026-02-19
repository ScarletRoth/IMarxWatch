# IMarxWatch - Cinema Booking Application

A PHP-based web application for booking movie tickets using a clean MVC architecture without frameworks.

## Quick Start

### Installation

```bash
# 1. Clone the repository
git clone <repository-url>
cd IMarxWatch

# 2. Configure database (edit config/database.php)
# 3. Initialize database
php config/init_db.php

# 4. Start development server  
php -S localhost:8000 -t public

# 5. Open in browser
http://localhost:8000
```

## Features

- User Registration & Secure Login  
- Movie Listing & Session Management  
- Seat Selection & Booking System  
- User Profile Management  
- Admin Dashboard  
- SQL Injection Prevention (Prepared Statements)  
- XSS Protection (HTML Escaping)  
- Secure Password Hashing (Argon2ID)  
- Session Management with Timeout  
- Role-Based Access Control  

## Security

- All database queries use prepared statements
- Password hashing with Argon2ID
- Session expiration after 30 minutes of inactivity
- XSS protection through HTML escaping
- CSRF protection via session regeneration
- Email format validation
- Minimum 8-character password requirement

## Documentation

See README.md for detailed information about installation, configuration, and usage.

## Project Info

**Version**: 1.0.0  
**Last Updated**: February 2026  
**Status**: Not Completed

Created for Ynov PHP course - IMarxWatch Cinema Booking System

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