<?php
//Permet le routage des routes de l'url
//Constant qui contiendra le chemin vers ce fichier index.php
define('ROOT',str_replace('index.php','',$_SERVER["SCRIPT_FILENAME"]));

// On charge tout les middlewares
require_once(ROOT."src/Middleware/DotEnvEnvironment.php");
//On charge les variables d'environemments
(new DotEnvEnvironment)->load(ROOT);
require_once(ROOT.'src/Middleware/AbstractModel.php');
require_once(ROOT.'src/Middleware/AbstractController.php');

// On sépare les params
$params = explode('/',$_GET["p"]);
// Supprime le premier '/'
$params = array_slice($params,1);

if(!$params[0]){
    route("home");
}
else{
    
    $controller = $params[0];
    // Si le paramètre action est définie alors on modifier le paramètre action sinon on laisse par défaut
    isset($params[1]) ?  route($controller,$params[1]) : route($controller);
    
}

function route(string $controller, string $action = "index"):void{
    $controller = $controller."Controller";
    $controller = ucfirst($controller);
    require_once(ROOT.'src/Controller/'.$controller.'.php');
    $controller = new $controller();
    if(method_exists($controller,$action)){
        http_response_code(200);
        $controller->$action();
    } else{
        http_response_code(404);
        echo "La page demandée n'existe pas!";
    }
}