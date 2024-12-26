# Documentation du Projet Framework PHP Minimaliste

Ce document décrit la structure, les fonctionnalités et l'utilisation du projet afin de faciliter votre compréhension et votre productivité si vous revenez après une longue pause.

---

## Table des Matières

1. [Introduction](#introduction)
2. [Structure du Projet](#structure-du-projet)
3. [Configuration](#configuration)
4. [Fonctionnement des Routes](#fonctionnement-des-routes)
5. [Les Contrôleurs](#les-contrôleurs)
6. [Les Modèles](#les-modèles)
7. [Gestion des Vues](#gestion-des-vues)
8. [Gestion des Fichiers Uploadés](#gestion-des-fichiers-uploadés)
9. [Authentification](#authentification)
10. [Pages d'Erreur](#pages-d'erreur)
11. [Commandes CLI Personnalisées](#commandes-cli-personnalisées)
12. [Exemple de Workflow](#exemple-de-workflow)

---

## Introduction

Ce projet est un **framework PHP minimaliste** conçu pour vous aider à développer rapidement des applications web sans dépendre de frameworks tiers. Il suit une architecture MVC simplifiée.

---

## Structure du Projet

```plaintext
myframphp/
├── app/
│   ├── controllers/  # Contrôleurs
│   ├── models/       # Modèles
│   └── views/        # Vues
├── routes/
│   └── Router.php    # Système de routage
├── public/           # Répertoire de stockage des fichiers publics
│   ├── assets/       # Fichiers publics
│   └── uploads/      # Répertoire de stockage des fichiers uploadés
├── cli/              # Commandes CLI personnalisées
|   ├── Help.php      # Gère la commande help 
|   ├── Maker.php      # Gère les commandes de création des fichiers
|   └── Deleter.php    # Gère la commande de suppression des fichiers
├── configs/          # Fichiers de configuration
|   └── constantes.php    # Constantes
├── .htaccess         # Réécriture d'URL pour un routage propre
├── index.php         # Point d'entrée principal 
└── cli.php           # Commandes cli
```

---

## Configuration

### Fichier `constantes.php`
Ce fichier contient les configurations de base :
- **Connexion à la base de données** (`DB_NAME`, `DB_USER`, `DB_PASSWORD`)
- **Chemins** : Modèles, vues, contrôleurs.

Exemple de configuration :

```php
define("DB_NAME", "nom_de_la_base");
define("DB_USER", "root");
define("DB_PASSWORD", "motdepasse");
define("__models", "app/models/");
define("__controllers", "app/controllers/");
define("__views", "app/views/");
define("__public", "public/");
```

### Fichier `.htaccess`
Utilisé pour les routes propres. Redirige toutes les requêtes vers `index.php`.

```apache
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

---

## Fonctionnement des Routes

### Classe `Router`
La classe `Router` analyse l'URL et détermine :
- L'entité (exemple : `auth` pour l'authentification)
- L'action (exemple : `login` ou `register`)
- Les options supplémentaires (exemple : ID dans `user/edit/1`).

Exemple d'utilisation dans `index.php` :

```php
require_once '../routes/Router.php';
$router = new Router(__url);
$router->dispatch();
```

---

## Les Contrôleurs

Tous les contrôleurs héritent de la classe `Controller`. Exemple :

```php
class UserController extends Controller {
    public function register() {
        $this->render('register');
    }
}
```

Utilisez `$this->render('view_name', $data)` pour charger une vue.

---

## Les Modèles

Tous les modèles héritent de la classe `Model`. 

### CRUD avec la classe `Model`

1. **Créer une ligne** :
   ```php
   $model = new Model();
   $model->create('users', ['username' => 'John', 'email' => 'john@example.com']);
   ```

2. **Sélectionner des lignes** :
   ```php
   $model->selectAll('users');
   $model->selectWhere('users', ['role' => 'admin']);
   ```

3. **Modifier une ligne** :
   ```php
   $model->update('users', ['email' => 'newemail@example.com'], 'id', 1);
   ```

4. **Supprimer une ligne** :
   ```php
   $model->delete('users', 'id', 1);
   ```

---

## Gestion des Vues

Les vues sont des fichiers PHP situés dans `app/views/{model}/{view}.php`. Elles peuvent accéder aux données passées via `$this->render`.

Exemple dans un contrôleur :
```php
$this->render('dashboard', ['user' => $user]);
```

---

## Gestion des Fichiers Uploadés

La méthode `handleFile` dans `Controlleur.php` gère les fichiers uploader :

```php
$user = new User();
$storedPath = $user->handleFile(
    $_FILES['uploaded_file'], 
    ['image/jpeg', 'image/png'], 
    5 * 1024 * 1024
);
```

---

## Authentification

### Modèle `Auth`

Gère l'inscription et la connexion :
```php
public function login($email, $password) {
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}
```

### Contrôleur `AuthController`

- **Connexion** :
    ```php
    public function signIn() {
        $this->authModel = new Auth();
        $user = $this->authModel->login($_POST['email'], $_POST['password']);
        if ($user) {
            $_SESSION['user'] = $user;
            $this->render('dashboard', ['user' => $user]);
        } else {
            $this->render('login', ['error' => 'Identifiants incorrects.']);
        }
    }
    ```

---

## Pages d'Erreur

### 404 Not Found

Ajoutez une action dédiée dans le contrôleur générique :
```php
public function show404() {

    $viewPath = __views . "/errors/404.php";
    
    if (file_exists($viewPath)) {
        require_once $viewPath;
    } else {
        echo "\nLa vue '404' est introuvable.";
    }
}
```

Ajoutez une redirection dans `Router` :
```php
header("Location: ".BASE_URL."/errors/404");
```

---

## Commandes CLI Personnalisées

### Génération de Modèles et Contrôleurs
La commande CLI suivante vous permet de créer un modèle, son contrôleur et ses vues facilement :
```bash
php cli.php make -m User
```
Avec cette commande les fichiers suivantes seront créés automatiquement :
- Le modele : `models/User.php`.
- Le contrôleur : `controllers/UserController.php`.
- Les vues : (`views/users/index.php`, `views/users/create.php`, `views/users/edit.php`, `views/users/delete.php`).

### Suppression de Modèles
Supprimez un modèle et ses fichiers associés :
```bash
php cli.php delete -m User
```
Avec cette commande tous les fichiers liés au modèle `User` (le modèle, le controlleur et les vues)  seront supprimés.

---

## Rappels

- Toutes les entités doivent suivre le format **Nom + Controller** (exemple : `UserController`).
- Respectez la structure des vues (`views/{model}/{view}.php`).
- Stockez les fichiers publics dans `public/`.

---
