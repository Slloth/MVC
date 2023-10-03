<?php

namespace Core;

class Autoloader{
    static public function register():void{
        spl_autoload_register([__CLASS__,"autoload"]);
    }

    /**
     * On récupère dans $class la totalité du namespace de la class concerné.  
     * Puis on échange les separateurs par '/'.  
     * Enfin on rajoute le chemin complet vers le fichier et on test si le fichier existe bien puis on le charge
     *
     * @param string $class
     * @return void
     */
    static private function autoload(string $class){
        $class = str_replace('\\',DIRECTORY_SEPARATOR,$class);
        $file = __DIR__.'/'.$class.'.php';
        file_exists($file) ? require_once $file : http_response_code(404); include_once ROOT.'/App/Views/errors/404.php';
    }
}