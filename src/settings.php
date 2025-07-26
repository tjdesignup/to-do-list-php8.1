<?php
define('DEBUG', false);

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function($className){
    $className = str_replace("\\","/",str_replace("App\\","",$className));
    $file = __DIR__ . "/../src/" . $className .".php";
    if(file_exists($file))
    {
        require_once $file;
    }
});