# PHP MySQL Database Starter Class

Modèle de départ léger en PHP natif fournissant une classe `Database` basée sur le patron de conception **Singleton** autour de **PDO**, avec chargement automatique des variables d'environnement depuis un fichier `.env`.

## Structure du modèle

```text
├── db/
│   └── Database.php    # Classe Singleton de connexion PDO + parseur .env intégré
├── .env.example        # Modèle de configuration des identifiants SQL
