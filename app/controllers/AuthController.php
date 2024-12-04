<?php

require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/Controller.php';

class AuthController extends Controller
{
    private Auth $authModel;

    public function __construct()
    {
        $this->authModel = new Auth();
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function login()
    {
        $this->render('login');
    }

    /**
     * Traite les données du formulaire de connexion.
     */
    public function signIn()
    {
        if (isset($_POST['signIn'])) {
            
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->authModel->login($email, $password);

            if ($user) {
                session_start();
                $_SESSION['user'] = $user;
                $this->render('dashboard',['user'=>$_SESSION['user']]);
                $this->redirectTo("/auth/dashboard");
            } else {
                $this->render('login', ['error' => 'Identifiants incorrects.']);
            }
        }
    }

    /**
     * Affiche le formulaire d'inscription.
     */
    public function register()
    {
        $this->render('register');
    }

    /**
     * Traite les données du formulaire d'inscription.
     */
    public function signUp()
    {
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->authModel->userExists($email)) {
            $this->render('register', ['error' => 'Cet utilisateur est déjà inscrit.']);
        } else {
            if ($this->authModel->register(['nom' => $nom, 'email' => $email, 'password' => $password])) {

                $this->redirectTo("/auth/login");

            } else {
                echo "Une erreur est survenue lors de l'inscription.";
                $this->render('register', ['error' => 'Une erreur est survenue.']);
            }
        }
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout()
    {
        session_start();
        session_destroy();
        
        $this->redirectTo("/auth/login");
    }

    public function dashboard()
    {
        session_start();
        if (isset($_SESSION['user'])) {
            $this->render('dashboard', ['user' => $_SESSION['user']]);
        } else {
            // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
            $this->redirectTo("/auth/login");
        }
    }

}
