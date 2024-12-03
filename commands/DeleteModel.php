<?php

require_once 'Terminal.php';

class DeleteModel extends Terminal
{
    private string $modelName;
    private string $controllerName;
    private string $viewsDir;
    private string $modelFile;
    private string $controllerFile;

    public function __construct(string $modelName)
    {
        parent::__construct($modelName);

        $this->modelName = ucfirst($modelName);
        $this->controllerName = "{$this->modelName}Controller";
        $baseDir = __DIR__ . '/../app';
        $this->viewsDir = "{$baseDir}/views/" . strtolower($modelName);
        $this->modelFile = "{$baseDir}/models/{$this->modelName}.php";
        $this->controllerFile = "{$baseDir}/controllers/{$this->controllerName}.php";
    }

    /**
     * Supprime tous les fichiers et répertoires liés au modèle.
     */
    public function execute(): void
    {
        if (!$this->modelExist()) {
            echo $this->error("Le modèle '{$this->modelName}' n'existe pas.\n");
            return;
        }

        // Supprimer le fichier du modèle
        $this->deleteFile($this->modelFile);

        // Supprimer le fichier du contrôleur
        $this->deleteFile($this->controllerFile);

        // Supprimer le dossier des vues
        $this->deleteDirectory($this->viewsDir);

        echo $this->success("Tous les fichiers et répertoires du modèle '{$this->modelName}' ont été supprimés! \n");
        
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

}
