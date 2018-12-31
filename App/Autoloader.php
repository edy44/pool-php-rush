<?php
namespace App;

/**
 * Permet de charger toutes les classes automatiquement
 * Class Autoloader
 * @package App
 */
class Autoloader {

    /**
     * Appel de la fonction Autoload en fonction du nom de la classe
     */
    public static function register() {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Permet de charger une classe en fonction de son Namespace
     * @param $class
     */
    private static function autoload($class)
    {
        if (strpos($class, __NAMESPACE__ . '\\') === 0) {

            $class = str_replace(__NAMESPACE__ . '\\', '', $class);
            $class = str_replace('\\', '/', $class);
            require __DIR__.DS.$class.'.php';
        }
    }

}