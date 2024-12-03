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
        include 'commands/help.php';
        break;

    case 'make':
        handleMakeCommand($type, $name);
        break;

    case 'delete':
        handleDelete($type, $name);
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
function handleMakeCommand(?string $type, ?string $name): void
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

            include 'commands/make.php';
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
function handleDelete(?string $type, ?string $name): void
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

            require_once 'commands/delete.php';
            $deleter = new Delete($name);
            if ($deleter->modelExist()) {
                $deleter->execute();
            } else {
                die("Veuillez fournir un modèle existant pour la suppression d'un modèle.\n");
            }
            
            
            break;

        default:
            die("Type '$type' non reconnu.\nUtilisez '--help' ou '-h' pour voir les options disponibles.\n");
    }
}
