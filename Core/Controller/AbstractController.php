<?php

namespace Core\Controller;

//Permet a tout les controllers qui en hérite d'avoir les méthodes basiques
abstract class AbstractController{
    /**
     * Rend un page
     *
     * @param string $ficher
     * @param array $options
     * @return void
     */
    public function render(string $ficher, array $options = []){
        $controllerPath = str_replace(['Controllers','Controller','App'],'',get_class($this));
        $controllerPath = str_replace('\\','/',$controllerPath);
        $controllerPath = ROOT.'App/Views'.strtolower($controllerPath).'/'. $ficher.'.php';
        $this->renderBase($controllerPath,$options);
    }
    
    /**
     * Rend une page erreur
     *
     * @param string $ficher
     * @param array $options
     * @return void
     */
    public function renderError(string $ficher, array $options = []){
        http_response_code((int) $ficher);
        $controllerPath = ROOT.'App/Views/errors/'. $ficher.'.php';
        $this->renderBase($controllerPath,$options);
    }
    
    /**
     * Undocumented function
     *
     * @param string $controllerPath
     * @param array $options
     * @return void
     */
    private function renderBase(string $controllerPath, array $options = [])
    {
        // Récupère les valeurs du tableau pour en faire des variables à part entière
        extract($options);
        // On démarre le buffer
        ob_start();
        // Récupère le fichier php de la vue, a partire du nom du controller pour selectionner le bon dossier
        require_once($controllerPath);
        // On récupère les données du buffer et on le clore
        $main = ob_get_clean();
        
        require_once(ROOT.'App/Views/layouts/base.php');
        
    }
}