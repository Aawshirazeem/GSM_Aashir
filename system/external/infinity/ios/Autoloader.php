<?php
namespace ios;

class Autoloader {

    public static function load($className) {
        if (strpos($className, "ios") !== 0) {
            return;
        }

        $pos = strpos($className, '\\');
        $className = substr($className, $pos + 1);

        require_once(__DIR__ . '/' . strtr($className, '\\_', '//') . '.php');
    }

    public static function register() {
        spl_autoload_register(__NAMESPACE__ . '\\Autoloader::load');
    }

}