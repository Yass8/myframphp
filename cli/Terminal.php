<?php

class Terminal
{
    // Codes de couleur ANSI
    const RED = "\033[31m";
    const GREEN = "\033[32m";
    const YELLOW = "\033[33m";
    const BLUE = "\033[34m";
    const MAGENTA = "\033[35m";
    const CYAN = "\033[36m";
    const WHITE = "\033[37m";
    const RESET = "\033[0m";  // Réinitialiser la couleur à la valeur par défaut

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

    /**
     * Affiche un message avec une couleur spécifiée.
     * 
     * @param string $message Le message à afficher.
     * @param string $color La couleur à appliquer.
     */
    public function coloredOutput(string $message, string $color): void
    {
        echo $color . $message . self::RESET . "\n";
    }

    /**
     * Affiche un message de succès en vert.
     * 
     * @param string $message Le message de succès.
     */
    public function success(string $message): void
    {
        $this->coloredOutput($message, self::GREEN);
    }

    /**
     * Affiche un message d'erreur en rouge.
     * 
     * @param string $message Le message d'erreur.
     */
    public function error(string $message): void
    {
        $this->coloredOutput($message, self::RED);
    }

    /**
     * Affiche un message d'avertissement en jaune.
     * 
     * @param string $message Le message d'avertissement.
     */
    public function warning(string $message): void
    {
        $this->coloredOutput($message, self::YELLOW);
    }

    /**
     * Affiche un message d'information en bleu.
     * 
     * @param string $message Le message d'information.
     */
    public function info(string $message): void
    {
        $this->coloredOutput($message, self::BLUE);
    }

    /**
     * Affiche un message avec une couleur personnalisée.
     * 
     * @param string $message Le message à afficher.
     * @param string $color La couleur à appliquer, sous forme de code ANSI.
     */
    public function custom(string $message, string $color): void
    {
        $this->coloredOutput($message, $color);
    }
}