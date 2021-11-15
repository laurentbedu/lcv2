<?php
class App{

    const MODE = "dev";
    //const CONTACT_EMAIL = "lmilliez200@gmail.com";
    const CONTACT_EMAIL = "bedulaurent@gmail.com";

    private static $classList;
    public static function init(){
        session_start();
        include_once $_SERVER['DOCUMENT_ROOT'].'/app/Utils.php';
        self::$classList = Utils::scanDirectory($_SERVER['DOCUMENT_ROOT'].'/app');
        spl_autoload_register(function($className) {
            include_once self::$classList[$className];
        });
    }

    
}