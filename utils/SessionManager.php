<?php

namespace utils;

/**
 * Classe Singleton de gestion des sessions PHP
 *
 * @author  Noah Quaghebeur
 * @version 1.0
 */
class SessionManager
{
    private static $instance = null;

    /**
     * Constructeur défini en privé créant une session PHP
     */
    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
        }
    }

    /**
     * Methode clone verrouillée via une exception
     */
    public function __clone()
    {
        throw new Exception('Impossible de cloner le gestionnaire de session.');
    }

    /**
     * Fournit l'unique instance du SessionManager
     * @return SessionManager
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Enregistre ou met à jour une valeur en session
     */
    public function set(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Enregistre plusieurs valeurs d'un coup via un tableau associatif
     * @example setMultiple(['id' => 1, 'nom' => 'Noah', 'lang' => 'fr'])
     */
    public function setMultiple(array $data)
    {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Récupère une valeur en session
     * @return String valeur en session ou default si la clé n'existe pas
     */
    public function get(string $key, mixed $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Vérifie si une clé existe en session
     * @return boolean vrai si la clé existe sinon faux
     */
    public function has(string $key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Supprime une clé spécifique de la session
     */
    public function remove(string $key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Retourne toutes les données actuellement en session
     */
    public function all()
    {
        return isset($_SESSION) ? $_SESSION : [];
    }

    /**
     * Régénère l'ID de session (indispensable après une connexion réussie)
     */
    public function regenerate()
    {
        session_regenerate_id(true);
    }

    /**
     * Message Flash : écrit un message qui sera supprimé automatiquement dès sa première lecture
     */
    public function flash(string $key, mixed $value = null)
    {
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return null;
        }

        $message = isset($_SESSION['_flash'][$key]) ? $_SESSION['_flash'][$key] : null;
        unset($_SESSION['_flash'][$key]);
        return $message;
    }

    /**
     * Vide et détruit complètement la session
     */
    public function destroy()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        self::$instance = null;
    }

    /**
     * Méthodes simplifiant l'utilisation de la classe permettant d'utiliser $session->nom = 'Noah' ou echo $session->lang
     */
    public function __set(string $key, mixed $value)
    {
        $this->set($key, $value);
    }

    public function __get(string $key)
    {
        return $this->get($key);
    }

    public function __isset(string $key)
    {
        return $this->has($key);
    }

    public function __unset(string $key)
    {
        $this->remove($key);
    }
}