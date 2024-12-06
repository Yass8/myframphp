<?php

class Router {
    private $url;

    private $action;

    private $entity;

    private $options = [];

    public function __construct(string $url)
    {
        $this->url = $url;

        $this->setup();
    }

    public function setup() {
        $urls = explode('/', $this->url);
    
        // L'entité est la première partie de l'URL, comme "home" ou "student"
        if (isset($urls[0]) && !empty($urls[0])) {
            $this->entity = $urls[0];
        } else {
            $this->entity = "home"; // Par défaut, c'est "home"
        }
    
        // L'action est la deuxième partie de l'URL
        if (isset($urls[1]) && !empty($urls[1])) {
            $this->action = $urls[1];
        } else {
            $this->action = "login"; // Par défaut, l'action est "login"
        }
    
        // Traiter les options supplémentaires (id, paramètres supplémentaires, etc.)
        if (count($urls) > 2) {
            for ($i = 2; $i < count($urls); $i++) {
                array_push($this->options, $urls[$i]);
            }
        }
    }

    function dispatch() {
        $entityClass = ucfirst($this->entity) . 'Controller';
        $entityFile = __DIR__ . '/../app/controllers/' . $entityClass . '.php';
    
        // Gestion des routes spéciales
        switch ($this->entity) {
            case "home":
                $this->loadController('HomeController', 'home');
                return;
    
            case "errors":
                if ($this->action == 404) {
                    $this->loadController('Controller', 'show404');
                } else {
                    $this->redirectToHome();
                }
                return;
        }
    
        // Vérification de l'existence du fichier de contrôleur
        if (file_exists($entityFile)) {
            require_once $entityFile;
            $this->executeAction(new $entityClass());
        } else {
            $this->redirectTo404();
        }
    }
    
    /**
     * Charge un contrôleur et exécute une action.
     *
     * @param string $controllerClass Nom de la classe du contrôleur
     * @param string $action Nom de l'action à exécuter
     */
    private function loadController(string $controllerClass, string $action): void {
        require_once __DIR__ . '/../app/controllers/' . $controllerClass . '.php';
        $controller = new $controllerClass();
        if (method_exists($controller, $action)) {
            $controller->{$action}();
        } else {
            $this->redirectTo404();
        }
    }
    
    /**
     * Exécute une action sur un contrôleur donné.
     *
     * @param object $controller Instance du contrôleur
     */
    private function executeAction(object $controller): void {
        if (method_exists($controller, $this->action)) {
            call_user_func_array([$controller, $this->action], $this->options);
        } else {
            $this->redirectToHome();
        }
    }
    
    /**
     * Redirige vers la page d'accueil.
     */
    private function redirectToHome(): void {
        header("Location: " . BASE_URL . "/home/");
        exit;
    }
    
    /**
     * Redirige vers la page 404.
     */
    private function redirectTo404(): void {
        header("Location: " . BASE_URL . "/errors/404");
        exit;
    }   

}