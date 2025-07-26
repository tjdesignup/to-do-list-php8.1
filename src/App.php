<?php 
namespace App;

class App {

    private Router $router;

    public function run():void
    {
            $container = $this->getContainer();
            $this->router = new Router($container);
            $uri = $_SERVER["REQUEST_URI"];
            $method = $_SERVER["REQUEST_METHOD"];
            $this->route($uri,$method);
    }
    
    private function getContainer():?Container
    {
        $container = new Container();
        $container->set("databaseConnection", fn()=>new DatabaseConnection(__DIR__ . '/../src/.env'));
        $container->set("sessionService", fn()=>new Services\SessionService());
        $container->set("validationService", fn()=>new Services\ValidationService());
        $container->set("baseRepository", fn($con)=> new Repositories\BaseRepository($con->get("databaseConnection")));
        $container->set("loginController",fn($con)=>new Controllers\LoginController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
        $container->set("homeController",fn($con)=>new Controllers\HomeController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
        $container->set("dashController",fn($con)=>new Controllers\DashController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
        $container->set("noteController",fn($con)=>new Controllers\NoteController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
        $container->set("logoutController",fn($con)=>new Controllers\LogoutController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
        $container->set("registerController",fn($con)=>new Controllers\RegisterController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService")));
        $container->set("unregisterController",fn($con)=>new Controllers\UnregisterController($con->get("baseRepository"),$con->get("sessionService"),$con->get("validationService"))); 
        return $container ?? null;
    }

    private function route(string $uri, string $method):void
    {
        $this->router-> getRoute($uri,$method);
        $controller = $this->router->controller;
        if($controller!==null)
        {   
            $content = $controller->getContent();
        }
        include 'layout.html';
    }
}