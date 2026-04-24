## 🚀 Tasklist App - Symfony

**Une application de gestion de tâches performante**
développée avec Symfony, Stimulus et Tailwind CSS.

---

### 🛠️ Installation et Configuration

Pour commencer, clonez le dépôt dans votre dossier de projets :

```bash
git clone https://github.com/LuzRivera154/phase3-symfony-tasklist-reloaded.git
cd phase3-symfony-tasklist-reloaded
```
#### 1. Vérifier les prérequis

Assurez-vous que votre environnement est prêt pour Symfony :
Bash
```bash
symfony check:requirements
```
#### 2. Installer les dépendances

Installez les bibliothèques PHP nécessaires avec Composer :
```bash

composer install
```
------
## ⚡ Lancer l'Application

Pour faire tourner l'app, vous devez exécuter deux commandes dans des terminaux séparés :

Serveur Local

#### Lancez le serveur:

```bash
symfony server:start
```

Une fois lancé, l'application est accessible sur : http://localhost:8000

#### Compilation Tailwind (Watcher)

Pour que les styles se mettent à jour automatiquement pendant que vous travaillez, lancez le watcher :

```bash
php bin/console tailwind:build --watch
```
-----
## 🗄️ Gestion de la Base de Données

Le projet utilise SQLite pour plus de simplicité. Si vous souhaitez explorer les données manuellement :

    Téléchargement : Installez DB Browser for SQLite.

    Ouvrir : Lancez le logiciel et cliquez sur "Ouvrir une base de données".

    Chemin : Allez dans le dossier du projet et sélectionnez le fichier :

        var/data_dev.db

Depuis cette interface, vous pourrez consulter vos tables.