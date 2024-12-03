<?php

class MakeModel
{
    private string $modelName;
    private string $controllerName;
    private string $viewsDir;
    private string $modelFile;
    private string $controllerFile;

    /**
     * Constructeur de la classe.
     *
     * @param string $modelName Le nom du modèle à créer.
     */
    public function __construct(string $modelName)
    {
        $this->modelName = ucfirst($modelName);
        $this->controllerName = "{$this->modelName}Controller";
        $baseDir = __DIR__ . '/../app';
        $this->viewsDir = "{$baseDir}/views/" . strtolower($modelName);
        $this->modelFile = "{$baseDir}/models/{$this->modelName}.php";
        $this->controllerFile = "{$baseDir}/controllers/{$this->controllerName}.php";
    }

    /**
     * Exécute le processus de création des fichiers pour le modèle.
     */
    public function execute(): void
    {
        $this->createModel();
        $this->createController();
        $this->createViews();
        $this->displaySuccessMessage();
    }

    /**
     * Crée le fichier du modèle s'il n'existe pas.
     */
    private function createModel(): void
    {
        $content = "<?php\n\n" .
            "require_once __DIR__ . '/../../core/Model.php';\n\n" .
            "class {$this->modelName} extends Model {\n" .
            "    // Modèle: {$this->modelName}\n" .
            "}\n";

        $this->createFile($this->modelFile, $content, "Modèle créé : {$this->modelFile}");
    }

    /**
     * Crée le fichier du contrôleur s'il n'existe pas.
     */
    private function createController(): void
    {
        $content = "<?php\n\n" .
            "require_once __DIR__ . '/../../core/Controller.php';\n\n" .
            "class {$this->controllerName} extends Controller {\n" .
            "    // Contrôleur: {$this->controllerName}\n" .
            "}\n";

        $this->createFile($this->controllerFile, $content, "Contrôleur créé : {$this->controllerFile}");
    }

    /**
     * Crée les fichiers de vue associés au modèle.
     */
    private function createViews(): void
    {
        $this->createDirectory($this->viewsDir);

        $views = [
            'edit.php' => "<!-- Vue: edit -->",
            'create.php' => "<!-- Vue: create -->",
            'index.php' => "<!-- Vue: index -->",
        ];

        foreach ($views as $fileName => $content) {
            $this->createFile("{$this->viewsDir}/$fileName", $content, "Vue créée : {$this->viewsDir}/$fileName");
        }
    }

    /**
     * Affiche un message de succès après la création des fichiers.
     */
    private function displaySuccessMessage(): void
    {
        echo "Fichiers créés :\n";
        echo " + {$this->modelFile}\n";
        echo " + {$this->controllerFile}\n";
        echo " + Dossier de vues : {$this->viewsDir}\n";
    }

    /**
     * Crée un fichier avec un contenu donné s'il n'existe pas.
     *
     * @param string $filePath Chemin du fichier.
     * @param string $content Contenu du fichier.
     * @param string $successMessage Message affiché en cas de succès.
     */
    private function createFile(string $filePath, string $content, string $successMessage): void
    {
        if (!file_exists($filePath)) {
            file_put_contents($filePath, $content);
            echo $successMessage . "\n";
        } else {
            echo "Fichier déjà existant : $filePath\n";
        }
    }

    /**
     * Crée un répertoire s'il n'existe pas.
     *
     * @param string $dirPath Chemin du répertoire.
     */
    private function createDirectory(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
            echo "Répertoire créé : $dirPath\n";
        } else {
            echo "Répertoire déjà existant : $dirPath\n";
        }
    }
}
