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

    /**
     * Valide les champs d'entrée en fonction des règles définies.
     *
     * @param array $fields Les données des champs à valider, avec le nom du champ comme clé et sa valeur comme contenu.
     * @param array $rules Les règles de validation pour chaque champ, où chaque clé est un champ et chaque valeur est un tableau des règles à appliquer.
     *                      Exemple :
     *                      [
     *                          'username' => ['required' => true, 'min' => 3, 'max' => 20],
     *                          'email' => ['required' => true, 'email' => true],
     *                          'password' => ['required' => true, 'min' => 6]
     *                      ]
     *
     * @return array Un tableau des erreurs, avec le nom du champ comme clé et le message d'erreur comme contenu.
     *               Si aucune erreur n'est détectée, retourne un tableau vide.
     *
     * Exemple d'utilisation :
     * $fields = ['username' => 'Jo', 'email' => 'invalid-email', 'password' => '123'];
     * $rules = ['username' => ['required' => true, 'min' => 3], 'email' => ['email' => true], 'password' => ['min' => 6]];
     * $errors = $this->validateInput($fields, $rules);
     */
    public function validateInput(array $fields, array $rules): array
    {
        $errors = [];

        // Parcourt chaque champ et ses règles associées.
        foreach ($rules as $field => $constraints) {
            $value = $fields[$field] ?? ''; // Récupère la valeur du champ ou une chaîne vide si elle est absente.

            // Parcourt chaque règle pour le champ en cours.
            foreach ($constraints as $constraint => $parameter) {
                // Vérifie si le champ est requis et vide.
                if ($constraint === 'required' && $parameter && empty($value)) {
                    $errors[$field] = ucfirst($field) . " est requis."; // Ajoute une erreur si la condition est remplie.
                    break; // Arrête la vérification des autres règles pour ce champ, car "required" est prioritaire.
                }

                // Vérifie les autres règles uniquement si le champ n'est pas vide.
                if (!empty($value)) {
                    switch ($constraint) {
                        case 'email': // Valide le format d'une adresse email.
                            if ($parameter && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $errors[$field] = "L'adresse email n'est pas valide.";
                            }
                            break;

                        case 'min': // Vérifie la longueur minimale.
                            if (strlen($value) < $parameter) {
                                $errors[$field] = ucfirst($field) . " doit comporter au moins {$parameter} caractères.";
                            }
                            break;

                        case 'max': // Vérifie la longueur maximale.
                            if (strlen($value) > $parameter) {
                                $errors[$field] = ucfirst($field) . " ne doit pas dépasser {$parameter} caractères.";
                            }
                            break;

                        default:
                            break;
                    }
                }
            }
        }

        return $errors; // Retourne les erreurs détectées ou un tableau vide s'il n'y a aucune erreur.
    }

}