<?php
//placez ce fichier dans un dossier db ou autre dossier prévu pour
$envPath = __DIR__ . '/../.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}

/**
 * Classe Singleton de connexion à un serveur MySQL
 *
 * @author  Noah Quaghebeur
 * @version 1.0
 */
class Database
{
    static private $PDO = NULL;
    static private $Instance = NULL;

    /**
     * Constructeur défini en privé créant une connexion MySQL
     */
    private function __construct(){
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_NAME'],
            $_ENV['DB_CHARSET']
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$PDO = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
    }

    /**
     * Methode de destruction de l'instance MySQL
     */
    public function __destruct() {
        self::$PDO = null ;
    }

    /**
     * Methode clone verrouillée via une exception
     */
    public function __clone() {
        throw new Exception('Impossible de cloner une connexion SQL');
    }


    /**
     * Méthode pour l'appel des fonctions de l'objet PDO quand
     * elles ne sont pas définies dans la classe
     *
     * @param type $method
     * @param type $params
     */
    public function __call($method, $params) {
        if(self::$PDO == NULL){
            self::__construct();
        }

        return call_user_func_array(array(self::$PDO, $method), $params);
    }

    /**
     * Fournit l'unique instance du Singleton
     *
     * @return Database
     */
    static public function getInstance(){
        if(!(self::$Instance instanceof self)){
            self::$Instance = new self();
        }
        return self::$Instance;
    }
}