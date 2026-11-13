# PHP Kit Starter Class

Modèle de départ léger en PHP natif fournissant une classe `Database` basée sur le patron de conception **Singleton** autour de **PDO**, avec chargement automatique des variables d'environnement depuis un fichier `.env`,
plus un modèle de départ de la classe `SessionManager` pour la gestion des sessions PHP.

## Structure du modèle

```text
├── db/
│   └── Database.php          # Classe Singleton de connexion PDO + parseur .env intégré
├── utils/
│   └── SessionManager.php    # Classe Singleton de gestion de session PHP
├── .env.example              # Modèle de configuration des identifiants SQL
