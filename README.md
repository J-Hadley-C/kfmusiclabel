# Application de Collaboration Musicale

Cette application est conçue pour faciliter la collaboration entre différents types d’artistes (beatmakers, chanteurs, musiciens et producteurs). Elle permet aux utilisateurs de partager leurs créations musicales, de découvrir les travaux des autres, et de collaborer sur des projets en ligne.

## Fonctionnalités principales

### 1. Inscription et Gestion de Compte
- Les utilisateurs peuvent s’inscrire en choisissant un rôle spécifique : `Beatmaker`, `Chanteur`, `Musicien` ou `Producteur`.
- Après inscription, les utilisateurs reçoivent un **email de vérification** pour confirmer leur compte. Cela garantit que seuls les utilisateurs validés peuvent interagir sur la plateforme.
  
### 2. Tableau de Bord Artiste
- Un **tableau de bord unique pour les artistes** regroupe toutes les fonctionnalités et est personnalisé en fonction du rôle de l’utilisateur :
  - `Beatmaker` : Accès à des fonctionnalités et outils liés à la création de beats.
  - `Chanteur` : Accès aux fonctionnalités pour la gestion des enregistrements vocaux.
  - `Musicien` : Options pour partager et gérer des morceaux musicaux.
  - `Producteur` : Outils de gestion et de production musicale.
- **Vue conditionnelle par rôle** : Le tableau de bord adapte l’affichage et les fonctionnalités pour chaque type d’artiste.
- **Limite de contenu** : Chaque artiste peut uploader **jusqu’à trois fichiers musicaux** pour présenter un échantillon de son travail, ce qui permet de limiter l’espace tout en facilitant les collaborations.

### 3. Gestion des Collaborations
- Les utilisateurs peuvent **créer des collaborations** et inviter d’autres artistes à rejoindre des projets.
- **Liste des collaborations** : Les utilisateurs accèdent à la liste de toutes les collaborations auxquelles ils participent depuis leur tableau de bord.
- **Détails de chaque collaboration** : Chaque collaboration affiche les informations détaillées sur les participants et le statut du projet, permettant un suivi optimal de l'avancement des projets.

### 4. Tableau de Bord Administrateur
- Accessible uniquement aux administrateurs, ce tableau de bord permet de gérer les utilisateurs, les rôles, et les projets de collaboration, offrant ainsi un contrôle global sur l’application.

## Prérequis Techniques

- PHP 8.x
- Symfony 5/6
- Base de données MySQL
- Composer
- Symfony CLI (pour servir l'application en local)

## Installation

1. Clonez le dépôt et accédez au répertoire du projet :
   ```bash
   git clone <url_du_dépôt>
   cd <nom_du_projet>

Installez les dépendances avec Composer :
composer install

Configurez la connexion à la base de données dans le fichier .env, puis exécutez les migrations pour générer la structure des tables :
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

Démarrez le serveur Symfony 
symfony serve

## UTILISATION

1. **INSCRIPTION ET CONNEXION** : Les utilisateurs s’inscrivent, choisissent leur rôle, et confirment leur adresse email.
2. **TABLEAU DE BORD ET AJOUT DE CONTENU** : Chaque artiste peut ajouter jusqu’à trois fichiers musicaux dans son tableau de bord, en fonction de son rôle.
3. **COLLABORATION** : Les artistes peuvent initier et gérer des collaborations depuis leur tableau de bord.


Contribution
Les contributions pour améliorer les fonctionnalités ou corriger des erreurs sont les bienvenues. Veuillez créer une pull request pour chaque modification majeure, ou signaler les bugs via les issues.

Licence
Ce projet est sous licence Licence MIT.

git add README.md
git commit -m "Ajout du README pour l'application"
git push origin main  # ou la branche de votre choix