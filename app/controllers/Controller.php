<?php

class Controller{

    public function render(string $view, array $data = []): void
    {
        // Déduit le modèle à partir du nom du contrôleur (ex: UserController -> user)
        $model = strtolower(str_replace('Controller', '', (new ReflectionClass($this))->getShortName()));

        // Rend les données accessibles comme variables
        extract($data);

        // Chemin vers le fichier de vue
        $viewPath = __DIR__ . "/../views/{$model}/{$view}.php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "\nLa vue '{$view}' pour le modèle '{$model}' est introuvable.";
        }
    }

    public function show404(){
        $viewPath = __DIR__ . "/../views/errors/404.php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "\nLa vue '404' est introuvable.";
        }
    }

    public function redirectTo(string $url) : void {
        header("Location: " . BASE_URL . $url);
        exit;
    }
}