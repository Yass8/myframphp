#!/usr/bin/env php

<?php

// Vérification du nombre d'arguments
if ($argc < 2) {
    die("Usage: php fram [command] [options]\n");
}

// Récupération des arguments
$action = $argv[1];
$type = $argv[2] ?? null;
$name = $argv[3] ?? null;

// Gestion des commandes
switch ($action) {
    case '--help':
    case '-h':
        require_once 'commands/Help.php';
        $help = new Help();
        $help->display();
        break;

    case 'make':
        handleMakeModel($type, $name);
        break;

    case 'delete':
        handleDeleteModel($type, $name);
        break;

    default:
        die("Commande '$action' non reconnue.\nUtilisez '--help' ou '-h' pour voir les options disponibles.\n");
}

/**
 * Gère la commande 'make'.
 *
 * @param string|null $type Le type de fichier à générer (ex. : 'model').
 * @param string|null $name Le nom du modèle à générer.
 */
function handleMakeModel(?string $type, ?string $name): void
{
    if (!$type) {
        die("Usage: php fram make [type] [Name]\n");
    }

    switch ($type) {
        case '-m':
        case 'model':
            if (!$name) {
                die("Veuillez fournir un nom pour le modèle.\nUsage: php fram make -m [ModelName]\n");
            }
            require_once 'commands/MakeModel.php';
            $maker = new MakeModel($name);
            $maker->execute();
            break;

        default:
            die("Type '$type' non reconnu.\nUtilisez '--help' ou '-h' pour voir les options disponibles.\n");
    }
}


/**
 * Gère la commande 'delete'.
 *
 * @param string|null $type Le type de fichier à générer (ex. : 'model').
 * @param string|null $name Le nom du modèle à générer.
 */
function handleDeleteModel(?string $type, ?string $name): void
{
    if (!$type) {
        die("Usage: php fram delete [type] [Name]\n");
    }

    switch ($type) {
        case '-m':
        case 'model':
            if (!$name) {
                die("Veuillez fournir un nom pour le modèle.\nUsage: php fram delete -m [ModelName]\n");
            }

            require_once 'commands/DeleteModel.php';
            $deleter = new DeleteModel($name);
            $deleter->execute();
            
            
            break;

        default:
            die("Type '$type' non reconnu.\nUtilisez '--help' ou '-h' pour voir les options disponibles.\n");
    }
}
