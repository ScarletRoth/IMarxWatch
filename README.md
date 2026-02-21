# IMarxWatch - Systeme de Reservation de Cinema

Application web basee sur PHP pour la reservation de billets de cinema utilisant une architecture MVC propre sans frameworks.

## Demarrage Rapide

### Installation

```bash
# 1. Cloner le repository
git clone <repository-url>
cd IMarxWatch

# 2. Configurer la base de donnees (editer config/database.php)
# 3. Initialiser la base de donnees
php config/init_db.php

# 4. Demarrer le serveur de developpement
php -S localhost:8000 -t public

# 5. Ouvrir dans le navigateur
http://localhost:8000
```

## Fonctionnalites

- Inscription utilisateur et connexion securisee
- Affichage des films et gestion des seances
- Selection des places et systeme de reservation
- Gestion du profil utilisateur
- Tableau de bord administrateur
- Prevention de l'injection SQL (requetes preparees)
- Protection XSS (echappement HTML)
- Hachage securise des mots de passe (Argon2ID)
- Gestion des sessions avec delai d'expiration
- Controle d'acces base sur les roles

## Securite

- Toutes les requetes de base de donnees utilisent des requetes preparees
- Hachage des mots de passe avec Argon2ID
- Expiration de session apres 30 minutes d'inactivite
- Protection XSS par echappement HTML
- Protection CSRF via regeneration de session
- Validation du format email
- Exigence d'un mot de passe d'au moins 8 caracteres

## Documentation

Consultez ce fichier README pour des informations detaillees sur l'installation, la configuration et l'utilisation.

## Informations du Projet

Version: 1.0.0
Derniere mise a jour: Fevrier 2026
Statut: Termine

Cree pour le cours PHP Ynov - Systeme de Reservation de Cinema IMarxWatch

## Structure du Projet

```
IMarxWatch/
├── config/
│   ├── database.php        Connexion PDO
│   ├── config.php          Constantes et parametres de session
│   ├── init_db.php         Initialisation de la base de donnees
│   ├── SessionManager.php  Gestionnaire de sessions
│   └── seed_db.php         Donnees d'exemple
├── controllers/
│   ├── AuthController.php  Authentification et gestion d'utilisateur
│   ├── MovieController.php Gestion des films et seances
│   ├── BookingController.php Gestion des reservations
│   └── AdminController.php Fonctionnalites administrateur
├── models/
│   ├── User.php            Modele pour la table 'users'
│   ├── Movie.php           Modele pour la table 'movies'
│   ├── Session.php         Modele pour la table 'sessions'
│   └── Booking.php         Modele pour la table 'bookings'
├── views/
│   ├── admin/              Vues administrateur
│   │   ├── dashboard.php   Tableau de bord admin
│   │   ├── movies/         Gestion des films
│   │   ├── users/          Gestion des utilisateurs
│   │   ├── sessions/       Gestion des seances
│   │   └── bookings/       Gestion des reservations
│   ├── bookings/           Vues de reservation
│   ├── movies/             Vues des films
│   ├── user/               Vues du profil utilisateur
│   ├── home.php            Page d'accueil
│   ├── login.php           Page de connexion
│   ├── signup.php          Page d'inscription
│   ├── 404.php             Page d'erreur
│   └── footer.php          Pied de page
├── public/                 Dossier accessible au public
│   ├── css/                Fichiers de style
│   ├── images/             Images et ressources
│   ├── js/                 Scripts JavaScript
│   └── index.php           Point d'entree (routeur)
├── sql/
│   └── database.sql        Script de creation des tables
└── README.md
```

## Etapes d'Installation Detaillees

### 1. Cloner le repository

```bash
git clone <url-du-repository>
cd IMarxWatch
```

### 2. Configurer la base de donnees

Editer le fichier `config/database.php` avec vos identifiants MySQL:

- Nom d'hote: localhost
- Utilisateur: root
- Mot de passe: (votre mot de passe)
- Port: 3306

### 3. Initialiser la base de donnees

```powershell
cd "C:\Chemin\Vers\IMarxWatch"
php config/init_db.php
```

### 4. Demarrer le serveur PHP

```powershell
php -S localhost:8000 -t public
```

### 5. Ouvrir dans le navigateur

```
http://localhost:8000
```

## Utilisation

### Accueil
Consultez les films a l'affiche et les sessions disponibles. Connectez-vous pour faire des reservations.

### Reservation
1. Connectez-vous avec votre compte utilisateur
2. Selectionnez un film dans la liste
3. Choisissez une seance disponible
4. Selectionnez les places desinees
5. Confirmez votre reservation

### Profil Utilisateur
Gerez votre profil, consultez l'historique de vos reservations et supprimez votre compte si necessaire.

### Panel Administrateur
Acces reserve aux administrateurs pour:
- Gerer les films (creer, modifier, supprimer)
- Gerer les utilisateurs
- Gerer les seances de cinema
- Visualiser les reservations
- Consulter les statistiques

## Requirements Techniques

- PHP 8.0 ou superieur
- MySQL 5.7 ou superieur
- PDO pour PHP
- Navigateur web moderne (Chrome, Firefox, Safari, Edge)

## Fonctionnalites de Securite

- Requetes SQL preparees pour prevenir les injections SQL
- Hachage des mots de passe avec Argon2ID
- Protection XSS avec echappement HTML
- Regeneration des sessions tous les 300 secondes
- Expiration des sessions apres 30 minutes d'inactivite
- Remember Me avec tokens securises
- Validation des emails
- Controle d'acces base sur les roles

## Notes Importantes

- La base de donnees sera creee et remplie avec des donnees d'exemple lors de l'execution de `init_db.php`
- Les credentials admin par defaut sont fournis dans les donnees d'exemple
- Pour des raisons de securite, modifiez les mots de passe par defaut apres l'installation initiale
- Utilisez HTTPS en production
