<?php

namespace Core;

use Error;

class Main{

    private array $params;

    public function start(){
        // On sépare les params
        $uri = $_SERVER["REQUEST_URI"];
        // On supprime le dernier '/' si il y'en a un
        if(!empty($uri) && $uri === '/' && $uri[-1] === '/'){
            $uri = substr($uri,0,-1); 
            http_response_code(301);
            //Code de redirection permanante
            header('Location: '.$uri);
        } 


        $this->params = explode('/',$uri);
        // Supprime le premier '/'
        $this->params = array_slice($this->params,1);
        if(empty($this->params[0])){
            $this->route("home");
        }
        elseif($this->params[0] != "assets"){
            // Si le paramètre action est définie alors on modifier le paramètre action sinon on laisse par défaut
            isset($this->params[1]) && $this->params[1] != "index" ?  $this->route($this->params[0],$this->params[1]) : $this->route($this->params[0]);
        }
        elseif($this->params[1] != 'img'){
            require_once ROOT.'Public'.$_SERVER["REQUEST_URI"];
        }
        else{
            header("Content-Type: image/gif");
            readfile( ROOT.'Public'.$_SERVER["REQUEST_URI"]);
        }
    }

    private function route(string $controller, string $action = "index"):void{
        $controller = ucfirst($controller); 
        $controller = "App\\Controllers\\".$controller."Controller";
        try{
            $controller = new $controller();
        }catch(Error $e){
            echo $e->getMessage();
        }
        if(method_exists($controller,$action)){
            http_response_code(301);
            unset($this->params[0]);
            unset($this->params[1]);
            call_user_func_array([$controller,$action],$this->params);
        } else{
            http_response_code(404);
            require_once ROOT.'App/Views/errors/404.php';
        }
    }
}


