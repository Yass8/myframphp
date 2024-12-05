<?php

class Help
{
    /**
     * Affiche l'aide générale.
     */
    public function display(): void
    {
        echo $this->getHeader();
        echo "Usage : php cli.php <commande> [options] [arguments]\n\n";
        echo "Commandes disponibles :\n";
        echo $this->formatCommand('make', "Crée un modèle, un contrôleur et des vues associés.");
        echo $this->formatCommand('delete', "Supprime un modèle, un contrôleur et les vues associés.");
        echo $this->formatCommand('--help, -h', "Affiche cette aide.");
        echo "\nExemples :\n";
        echo $this->formatExample("php cli.php make -m User", "Crée un modèle User.");
        echo $this->formatExample("php cli.php delete -m User", "Supprime le modèle User et ses fichiers associés.");
        echo "\n";
    }

    /**
     * Retourne une chaîne formatée pour l'en-tête.
     *
     * @return string
     */
    private function getHeader(): string
    {
        return "\033[32m" . str_repeat("=", 40) . "\n" .
               "FRAMEWORK CLI HELP\n" .
               str_repeat("=", 40) . "\033[0m\n\n";
    }

    /**
     * Formate une commande pour l'affichage de l'aide.
     *
     * @param string $command La commande
     * @param string $description La description de la commande
     * @return string
     */
    private function formatCommand(string $command, string $description): string
    {
        return sprintf("  \033[33m%-15s\033[0m %s\n", $command, $description);
    }

    /**
     * Formate un exemple pour l'affichage de l'aide.
     *
     * @param string $example L'exemple
     * @param string $description La description de l'exemple
     * @return string
     */
    private function formatExample(string $example, string $description): string
    {
        return sprintf("  \033[34m%-30s\033[0m %s\n", $example, $description);
    }
}
