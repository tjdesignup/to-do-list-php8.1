<?php
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

 function setContainer()
 {
    $container = new App\Container();
    $container->set("databaseConnection", fn()=>new App\DatabaseConnection(__DIR__ . '/../src/.env'));
    $container->set("sessionService", fn()=>new App\Services\SessionService());
    $container->set("validationService", fn()=>new App\Services\ValidationService());
    $container->set("baseRepository", fn($con)=> new App\Repositories\BaseRepository($con->get("databaseConnection")));
    $container->set("loginController",fn($con)=>new App\Controllers\LoginController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
    $container->set("homeController",fn($con)=>new App\Controllers\HomeController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
    $container->set("dashController",fn($con)=>new App\Controllers\DashController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
    $container->set("noteController",fn($con)=>new App\Controllers\NoteController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
    $container->set("logoutController",fn($con)=>new App\Controllers\LogoutController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
    $container->set("registerController",fn($con)=>new App\Controllers\RegisterController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
    $container->set("unregisterController",fn($con)=>new App\Controllers\UnregisterController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
 }