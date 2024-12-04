<?php

require_once __DIR__ . '/../../core/Model.php';

class Auth extends Model
{
    /**
     * Inscrit un nouvel utilisateur.
     *
     * @param array $data Données de l'utilisateur (ex: ['email' => 'John', 'password' => '1234'])
     * @return bool True si l'utilisateur est inscrit, False sinon
     */
    public function register(array $data): bool
    {
        
        $req = $this->pdo->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");

        // Hash du mot de passe avec bcrypt
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        return $req->execute([$data['nom'], $data['email'], $hashedPassword]);
    }

    /**
     * Vérifie les identifiants d'un utilisateur.
     *
     * @param string $email Nom d'utilisateur
     * @param string $password Mot de passe
     * @return bool|array False si l'utilisateur n'existe pas ou mot de passe incorrect, sinon les informations utilisateur
     */
    public function login(string $email, string $password): bool|array
    {
        
        $req = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $req->execute([$email]);

        $user = $req->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Vérifie si un utilisateur existe.
     *
     * @param string $email Nom d'utilisateur
     * @return bool True si l'utilisateur existe, False sinon
     */
    public function userExists(string $email): bool
    {

        $req = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $req->execute([$email]);

        return $req->fetchColumn() > 0;
    }
}
