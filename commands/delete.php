<?php

class Delete
{
    private string $modelName;
    private string $basePath;

    /**
     * Constructeur
     * @param string $modelName Nom du modèle à supprimer
     */
    public function __construct(string $modelName)
    {
        $this->modelName = ucfirst($modelName);
        $this->basePath = __DIR__ . '/../app';
    }

    /**
     * Supprime tous les fichiers et répertoires liés au modèle.
     */
    public function execute(): void
    {
        // Chemins des fichiers et répertoires
        $modelFile = "{$this->basePath}/models/{$this->modelName}.php";
        $controllerFile = "{$this->basePath}/controllers/{$this->modelName}Controller.php";
        $viewsDir = "{$this->basePath}/views/" . strtolower($this->modelName);

        // Supprimer le fichier du modèle
        $this->deleteFile($modelFile);

        // Supprimer le fichier du contrôleur
        $this->deleteFile($controllerFile);

        // Supprimer le dossier des vues
        $this->deleteDirectory($viewsDir);

        // Code ANSI pour réinitialiser la couleur après l'affichage
        $reset = "\033[0m";

        $color = "\033[32m";

        echo $color . "Tous les fichiers et répertoires du modèle '{$this->modelName}' ont été supprimés.\n" . $reset;
    }

    /**
     * Supprime un fichier s'il existe.
     *
     * @param string $filePath Chemin du fichier
     */
    private function deleteFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
            echo "Fichier supprimé : $filePath\n";
        } else {
            echo "Fichier introuvable : $filePath\n";
        }
    }

    /**
     * Supprime un répertoire et son contenu s'il existe.
     *
     * @param string $dirPath Chemin du répertoire
     */
    private function deleteDirectory(string $dirPath): void
    {
        if (is_dir($dirPath)) {
            $files = array_diff(scandir($dirPath), ['.', '..']);

            foreach ($files as $file) {
                $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
                if (is_dir($filePath)) {
                    $this->deleteDirectory($filePath);
                } else {
                    $this->deleteFile($filePath);
                }
            }

            rmdir($dirPath);
            echo "Répertoire supprimé : $dirPath\n";
        } else {
            echo "Répertoire introuvable : $dirPath\n";
        }
    }

    /**
     * Vérification du modèle s'il existe.
     *
     * @param string $fileName Nom du fichier
     */
    public function modelExist()
    {
        $modelFilePath = "{$this->basePath}/models/{$this->modelName}.php";

        if (file_exists($modelFilePath)) {
            return true;
        }

        return false;

    }
}
