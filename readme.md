Commandes CLI pour le Projet
Ce projet intègre une interface en ligne de commande (CLI) puissante et facile à utiliser, permettant de générer, gérer et supprimer automatiquement les fichiers nécessaires pour un modèle, un contrôleur et des vues associés. Voici une documentation complète pour l'utilisation des commandes CLI disponibles.

📜 Structure des Commandes
Les commandes CLI suivent cette syntaxe générale :

bash
Copier le code
php fram <commande> [options] [arguments]
📌 Commandes Disponibles
1. make - Générer un modèle, un contrôleur et des vues
Cette commande crée automatiquement :

Un fichier pour le modèle dans le répertoire app/models.
Un fichier pour le contrôleur dans le répertoire app/controllers.
Un dossier contenant les fichiers de vue dans le répertoire app/views.
Syntaxe :

bash
Copier le code
php fram make -m [ModelName]
Exemple :

bash
Copier le code
php fram make -m User
Résultat :

Création de :
app/models/User.php
app/controllers/UserController.php
app/views/user/ contenant index.php, create.php, et edit.php.
2. delete - Supprimer un modèle, un contrôleur et des vues
Cette commande supprime :

Le fichier du modèle dans app/models.
Le fichier du contrôleur dans app/controllers.
Le dossier des vues associé dans app/views.
Syntaxe :

bash
Copier le code
php fram delete -m [ModelName]
Exemple :

bash
Copier le code
php fram delete -m User
Résultat :

Suppression de :
app/models/User.php
app/controllers/UserController.php
Le dossier app/views/user/.
3. --help ou -h - Afficher l'aide
Affiche des informations détaillées sur les commandes disponibles et leur utilisation.

Syntaxe :

bash
Copier le code
php fram --help
ou

bash
Copier le code
php fram -h
Exemple de sortie :

sql
Copier le code
========================================
FRAMEWORK CLI HELP
========================================

Usage : php fram <commande> [options] [arguments]

Commandes disponibles :
  make            Crée un modèle, un contrôleur et des vues associés.
  delete          Supprime un modèle, un contrôleur et les vues associés.
  --help, -h      Affiche cette aide.

Exemples :
  php fram make -m User          Crée un modèle User.
  php fram delete -m User        Supprime le modèle User et ses fichiers associés.
🎨 Couleurs dans le Terminal
Les commandes CLI utilisent des couleurs pour améliorer la lisibilité :

Vert : En-têtes et messages de succès.
Jaune : Noms des commandes.
Bleu : Exemples de commandes.
⚙️ Pré-requis pour les Commandes CLI
1. Configuration du Script fram :
Assurez-vous que le fichier fram est exécutable et accessible.

Sous Linux/MacOS :

bash
Copier le code
chmod +x fram
Sous Windows : Vous pouvez exécuter directement la commande avec php fram.

2. Structure du Répertoire :
Les commandes supposent que votre projet respecte la structure suivante :

less
Copier le code
app/
  ├─ models/
  ├─ controllers/
  └─ views/
      └─ [model_name]/ (ex: user/)
💡 Conseils
Toujours utiliser des noms de modèle en PascalCase, par exemple : User, Product, etc.
Si vous avez besoin d'ajouter ou de modifier les commandes CLI, regardez les classes MakeModel, DeleteModel et Terminal dans le dossier commands.
🚀 Contribuer
Si vous souhaitez améliorer les fonctionnalités CLI ou signaler un bug, n'hésitez pas à ouvrir une issue ou une pull request sur le dépôt GitHub.

Avec cette CLI, gérer votre projet devient rapide et intuitif, vous permettant de vous concentrer sur le développement de vos fonctionnalités principales. 🎉
