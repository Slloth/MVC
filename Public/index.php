<?php

//On charge les variables d'environemments

use App\Models\Article;
use App\Repositories\ArticleRepository;
use Core\Autoloader;
use Core\Db\Db;
use Core\DotEnvEnvironment;

require_once "../autoloader.php";
Autoloader::register();
DotEnvEnvironment::dotEnvLoad();

$articleRepo = new ArticleRepository();
// $articles = $articleRepo->findBy(["name" => "Mon Super"],["id" => 'DESC']);
$articles = $articleRepo->findAll(["id"=>"DESC"]);
// $article = $articleRepo->find(2);
var_dump($articles!=FALSE ? $articles : "Aucune ressource trouvé");

// // On sépare les params
// $params = explode('/',$_GET["p"]);
// // Supprime le premier '/'
// $params = array_slice($params,1);

// if(!$params[0]){
//     route("home");
// }
// else{
//     // Si le paramètre action est définie alors on modifier le paramètre action sinon on laisse par défaut
//     isset($params[1]) && $params[1] != "index" ?  route($params[0],$params[1]) : route($params[0]);
// }


// function route(string $controller, string $action = "index"):void{
//     global $params;
//     $controller = ucfirst($controller);
//     $controller = $controller."Controller";
//     require_once(ROOT.'src/Controller/'.$controller.'.php');
//     $controller = new $controller();
//     if(method_exists($controller,$action)){
//         http_response_code(200);
//         unset($params[0]);
//         unset($params[1]);
//         call_user_func_array([$controller,$action],$params);
//     } else{
//         http_response_code(404);
//         echo "La page demandée n'existe pas!";
//     }
// }