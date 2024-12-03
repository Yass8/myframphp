<?php

// Définir les chemins des fichiers
$modelName = ucfirst($name);
$controllerName = "{$modelName}Controller";
$viewsDir = __DIR__ . "/../app/views/" . strtolower($name);
$modelFile = __DIR__ . "/../app/models/{$modelName}.php";
$controllerFile = __DIR__ . "/../app/controllers/{$controllerName}.php";

// Créer le modèle
createFileIfNotExists($modelFile, "<?php\n\n" .
    "require_once __DIR__ . '/../../core/Model.php';\n\n" .
    "class $modelName extends Model {\n" .
    "    // Modèle: $modelName\n" .
    "}\n");

// Créer le contrôleur
createFileIfNotExists($controllerFile, "<?php\n\n" .
    "require_once __DIR__ . '/../../core/Controller.php';\n\n" .
    "class $controllerName extends Controller {\n" .
    "    // Contrôleur: $controllerName\n" .
    "}\n");

// Créer les vues
createDirectoryIfNotExists($viewsDir);

$views = [
    'edit.php' => "<!-- Vue: edit -->",
    'create.php' => "<!-- Vue: create -->",
    'index.php' => "<!-- Vue: index -->",
];

foreach ($views as $fileName => $content) {
    createFileIfNotExists("$viewsDir/$fileName", $content);
}

// Afficher les fichiers créés
echo "Fichiers créés :\n" .
    " + app/models/{$modelName}.php\n" .
    " + app/ontrollers/{$controllerName}.php\n" .
    " + Les vues du modèle {$modelName}\n";

/**
 * Crée un fichier avec le contenu donné s'il n'existe pas déjà.
 *
 * @param string $filePath Le chemin du fichier.
 * @param string $content  Le contenu du fichier.
 */
function createFileIfNotExists(string $filePath, string $content): void
{
    if (!file_exists($filePath)) {
        file_put_contents($filePath, $content);
    }
}

/**
 * Crée un répertoire s'il n'existe pas déjà.
 *
 * @param string $dirPath Le chemin du répertoire.
 */
function createDirectoryIfNotExists(string $dirPath): void
{
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0755, true);
    }
}
