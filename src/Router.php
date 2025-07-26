<?php

namespace App;

class Router{

    public ?Controllers\BaseController $controller = null;
    private Container $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getRoute(string $uri, string $method):void
    {   
        $urlPath = $this->getPath($uri);
        $queryParams = $this->getQuery($uri);
        $controller = null;
        
    switch($urlPath){
    case "login":
        $controller = $this->container->get('loginController');
        if($method === "GET"){
            $controller->index();
        }elseif($method === "POST"){
            if(isset($queryParams['action'])){
                if($queryParams['action']==="userExistsEndpoint"){
                    $controller->userExistsEndpoint();
                    exit;               
                }elseif($queryParams['action']==="passwordVerifyEndpoint"){
                    $controller->passwordVerifyEndpoint();
                    exit;
                }
            }else{
            $controller->login();
            }
        }else{
        http_response_code(400);
        echo "Page not found";
        }
        break;
    case "home":
            $controller = $this->container->get('homeController');
            $controller->index();
        break;
    case "":
            $controller = $this->container->get('homeController');
            $controller->index();
        break;
    case "register":
        $controller = $this->container->get('registerController');
        if($method === "GET"){
                $controller->index();           
        }elseif($method === "POST"){
            if(isset($queryParams['action'])){
                if($queryParams['action']==="emailDomainValidationEndpoint"){
                    $controller->emailDomainValidationEndpoint();
                    exit;               
                }
                elseif($queryParams['action']==="emailExistsEndpoint"){
                    $controller->emailExistsEndpoint();
                    exit;
                }
            }else{
            $controller->register();
            }
        }else{
        http_response_code(400);
        echo "Page not found";
        }
        break;
    case "unregister":
        if(!($_SESSION["IS_AUTHENTICATED"] ?? false)){
            header("Location: /login");
            exit;
        }else{
            $controller = $this->container->get('unregisterController');
            if($method === "POST"){
                $controller->unregister();
            }elseif($method === "GET"){
                $controller->index();
            }else{
            http_response_code(400);
            echo "Page not found";
            }
        }
        break;
    case "logout":
        if(!($_SESSION["IS_AUTHENTICATED"] ?? false)){
            header("Location: /login");
            exit;
        }else{
            $controller =  $this->container->get('logoutController');
            if($method === "GET"){
                $controller->index();
            }else{
            http_response_code(400);
            echo "Page not found";
            }
        }
        break;
    case "dashboard":
        if(!($_SESSION["IS_AUTHENTICATED"] ?? false)){
            header("Location: /login");
            exit;
        }else{
            $controller =  $this->container->get('dashController');
            if($method === "GET"){
                $page = isset($queryParams["page"]) ? (int)$queryParams["page"] : 1;
                $controller->index($page);          
            }
            if($method === "POST" && $_POST['action']==="deleteAllNotes"){
                $controller->deleteAllNotes();          
            }
        }
        break;
    case "note":
        if(!($_SESSION["IS_AUTHENTICATED"] ?? false)){
            header("Location: /login");
            exit;
        }else{
            $controller =  $this->container->get('noteController');
            if($method === "GET"){
                $noteId = isset($queryParams["id"]) ? (int)$queryParams["id"] : null;
                $controller->index($noteId);          
            }elseif($method === "POST" && isset($_POST['action']) && $_POST['action']==="saveNote"){
                $controller->saveNote();          
            }elseif($method === "POST" && isset($_POST['action']) && $_POST['action']==="updateNote"){
                $noteId = isset($queryParams["id"]) ? (int)$queryParams["id"] : null;
                $controller->updateNote($noteId);
            }
        }
        break;
    default:
    http_response_code(400);
    echo "Page not found";
    }
    $this->controller = $controller;
}

    private function getPath(string $uri):string
    {
        $urlParts = parse_url($uri);
        return trim($urlParts["path"],"/");
    }
    private function getQuery(string $uri): array
    {
        parse_str(parse_url($uri)["query"] ?? "",$queryParams);
        return $queryParams;
    }
}