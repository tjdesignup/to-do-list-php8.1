<?php 
class App {

    

}

$uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

$router = new App\Router($container);
$router-> getRoute($uri,$method);
$controller = $router->controller;

if($controller!==null)
{   
    $content = $controller->getContent();
}
include 'layout.html';