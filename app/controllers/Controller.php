<?php

class Controller{

    /**
     * Affiche la vue avec des données
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    public function render(string $view, array $data = []): void
    {
        // Déduit le modèle à partir du nom du contrôleur (ex: UserController -> user)
        $model = strtolower(str_replace('Controller', '', (new ReflectionClass($this))->getShortName()));

        // Rend les données accessibles comme variables
        extract($data);

        
        $viewPath = __DIR__ . "/../views/{$model}/{$view}.php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "\nLa vue '{$view}' pour le modèle '{$model}' est introuvable.";
        }
    }

    /**
     * Page 404
     *
     * @return void
     */
    public function show404(){
        $viewPath = __views . "/errors/404.php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "\nLa vue '404' est introuvable.";
        }
    }

    /**
     * Redirection des pages
     *
     * @param string $url
     * @return void
     */
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

    /**
     * Valide les conditions pour l'upload d'un fichier.
     *
     * @param array $file Le fichier à valider ($_FILES['input_name'])
     * @param array $allowedTypes Types MIME acceptés (e.g., ['image/jpeg', 'image/png'])
     * @param int $maxSize Taille maximale du fichier en mega octets
     * @return array Retourne un tableau avec 'success' => true/false et un 'message'
     */
    public function validateFile(array $file, array $allowedTypes, int $maxSize): array
    {        
        $size = $maxSize * 1024 * 1024;

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Une erreur est survenue lors de l\'upload.'];
        }

        // Vérifie le type MIME
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Type de fichier non autorisé.'];
        }

        // Vérifie la taille du fichier  
        if ($file['size'] > $size) {
            return ['success' => false, 'message' => 'Le fichier est trop volumineux.'];
        }

        return ['success' => true, 'message' => 'Fichier valide.'];
    }

    /**
     * Déplace un fichier téléchargé vers le chemin de stockage.
     *
     * @param array $file Le fichier téléchargé ($_FILES['input_name'])
     * @param string $chemin Répertoire où déplacer le fichier (e.g: 'publications/images/')
     * @return array Retourne un tableau avec 'success' => true/false et un 'message'
     */
    public function moveFile(array $file, string $chemin): array
    {
        $destination = __publics . 'uploads' . $chemin;

        // Vérifie si le répertoire existe, sinon le créer
        if (!is_dir($destination)) {
            if (!mkdir($destination, 0777, true)) {
                return ['success' => false, 'message' => 'Impossible de créer le répertoire de stockage.'];
            }
        }

        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION); // Récupère l'extension du fichier
        $uniqueFileName = uniqid('file_', true) . '.' . $extension;

        // Construire le chemin complet du fichier
        $filePath = rtrim($destination, '/') . '/' . $uniqueFileName;

        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return [
                'success' => true,
                'message' => 'Fichier déplacé avec succès.',
                'path' => $filePath
            ];
        }

        return ['success' => false, 'message' => 'Échec du déplacement du fichier.'];
    }


}