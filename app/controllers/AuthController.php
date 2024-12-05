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

            $fields =[
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];
            $rules = [
                'email' => ['required' => true, 'email' => true],
                'password' => ['required' => true]
            ];

            $errors = $this->validateInput($fields, $rules);
            
            if (!empty($errors)) {
                $this->render('login', ['errors' => $errors]);
            } else {
                $user = $this->authModel->login($fields['email'], $fields['password']);

                if ($user) {
                    session_start();
                    $_SESSION['user'] = $user;
                    $this->render('dashboard',['user'=>$_SESSION['user']]);
                    $this->redirectTo("/auth/dashboard");
                } else {
                    $this->render('login', ['errors' => ['Identifiants incorrects.'] ]);
                }
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
        if (isset($_POST['signUp'])) {

            $fields =[
                'nom' => $_POST['nom'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];
            $rules = [
                'nom' => ['required' => true, 'min' => 3],
                'email' => ['required' => true, 'email' => true],
                'password' => ['required' => true, 'min' => 4]
            ];
            $errors = $this->validateInput($fields, $rules);

            if (!empty($errors)) {
                $this->render('register', ['errors' => $errors]);
            } else {
                if ($this->authModel->userExists($fields['email'])) {
                    $this->render('register', ['errors' => ['Cet utilisateur est déjà inscrit.']]);
                } else {
                    if ($this->authModel->register(['nom' => $fields['nom'], 'email' => $fields['email'], 'password' => $fields['password']])) {

                        $this->redirectTo("/auth/login");

                    } else {
                        $this->render('register', ['errors' => ['Une erreur est survenue.']]);
                    }
                }
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
