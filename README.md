# Emploi du Temps Personnel

Site de gestion de mon emploi du temps personnel me permettant  de créer, modifier, supprimer et consulter mes activités  et cours de la  journée.


## Fonctionnalités

- 🔐 **Inscription et connexion sécurisée** avec mot de passe hashé.
- ➕ **Ajouter une activité** ou un cours.
- ✏️ **Modifier une activité** existante.
- ❌ **Supprimer une activité**.
- 📅 **Visualisation de l'emploi du temps** par semaine.
- ⏰ **Rappel d'activités** avec notification et son d’alarme.
- 🚪 **Déconnexion sécurisée**.
- ⚠️ **Vérification de conflit horaire** pour les cours.


## Technologies utilisées

- **PHP 8+** pour le back-end.
- **MySQL / MariaDB** pour la base de données.
- **HTML5 & CSS3** pour le front-end.
- **JavaScript** pour les notifications et rappels.
- **Git & GitHub** pour le versioning c'est à dire garder l'historique des modifications de mon code


## Structure des fichiers

emploi_temps/
├── .git/                  # Dossier Git
├── assets/                # Tous les fichiers 
│   ├── css/
│   │   └── style.css
│   └── audio/
│       └── alarm.mp3
├── database/              # SQL dump et scripts de base
│   └── emploi_temps.sql
├── includes/              # Fichiers inclus
│   └── config.example.php
├── pages/                 # Pages PHP accessibles depuis le navigateur
│   ├── index.php          # Dashboard principal
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── add_task.php
│   ├── edit_task.php
│   ├── delete_task.php
│   └── add_course.php
├── .gitignore
└── README.md



## Installation en local (Kali Linux / Debian)

1. **Installer les dépendances** :
```
sudo apt update  
sudo apt install apache2 php mariadb-server php-mysql git -y
```
## 2. Démarrer Apache et MariaDB:
sudo service apache2 start
sudo service mariadb start

# 3. Cloner le dépot
git clone https://github.com/14-1Gabriel/emploi.git
cd emploi
```
```
# 4. Déplacer le projet dans Apache :
sudo mv emploi_temps /var/www/html/
sudo chmod -R 777 /var/www/html/emploi
```
```
# 5. Créer la base de données et un utilisateur dédié :

```bash
sudo mariadb
CREATE DATABASE emploi_temps;
CREATE USER 'emploi_user'@'localhost' IDENTIFIED BY 'motdepasse';
GRANT ALL PRIVILEGES ON emploi_temps.* TO 'emploi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
# 6. Importer la base de données :

sudo mariadb -u emploi_user -p emploi_temps < database/emploi_temps.sql

# 7. Configurer la connexion à la base :
# .Modifier config.php en:

```bash
 <?php
$host = "localhost";
$user = "emploi_user";
$password = "motdepasse";
$database = "emploi_temps";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>
```
# 8. Ouvrir le site dans le navigateur:

http://localhost/emploi_temps/pages/login.php


# AUTEUR
TCHIDEME GABRIEL
Email: tgab31686@gmail.com
Projet personnel
