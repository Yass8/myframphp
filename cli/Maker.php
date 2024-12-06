<?php

require_once 'Terminal.php';

class Maker extends Terminal
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
     * Exécute le processus de création des fichiers pour le modèle.
     */
    public function execute(): void
    {
        if ($this->modelExist()) {
            echo $this->error("Le modèle '{$this->modelName}' existe déjà.\n");
            return;
        }
        
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
            "require_once __models . '/Model.php';\n\n" .
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
        $model = lcfirst($this->modelName);
        $content = "<?php\n\n" .
            "require_once __controllers . '/Controller.php';\n" .
            "require_once __models . '/{$this->modelName}.php';\n\n" .
            "class {$this->controllerName} extends Controller {\n\n" .
            "    private {$this->modelName} \$model;\n\n" .
            "    public function __construct()\n".
            "    {\n".
            "        \$this->$model = new {$this->modelName}();\n".
            "    }\n\n".

            "    public function index() {\n\n".
            "        // \$$model = \$this->$model->`selectAll`('$model');\n\n".
            "        \$this->render('index'/*, ['$model' => \$$model]*/);\n".
            "    }\n\n".

            "    public function create() {\n".
            "       \$this->render('create');\n".
            "    }\n\n".

            "    public function edit(\$id) {\n".
            "        \$this->render('edit');\n".
            "    }\n\n".

            "    public function delete(\$id) {\n".
            "        \$this->render('delete');\n".
            "    }\n".
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
            'edit.php' => "<!-- Vue($this->modelName): edit -->\n<h3>Page d'édition du modèle '$this->modelName'</h3>",
            'create.php' => "<!-- Vue($this->modelName): create -->\n<h3>Page de création du modèle '$this->modelName'</h3>",
            'index.php' => "<!-- Vue($this->modelName): index -->\n<h3>Page d'index du modèle '$this->modelName'</h3>",
            'delete.php' => "<!-- Vue($this->modelName): delete -->\n<h3>Page de suppression du modèle '$this->modelName'</h3>",
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
        echo $this->success("Fichiers créés :\n
        + {$this->modelFile}\n
        + {$this->controllerFile}\n
        + Dossier de vues : {$this->viewsDir}\n
        ");
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
