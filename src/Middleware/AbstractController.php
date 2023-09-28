<?php

//Permet a tout les controllers qui en hérite d'avoir les méthodes basiques
#[AllowDynamicProperties]
abstract class AbstractController{
    public function loadModel(string $model){
        //Récupère le nom d'un modèle et le charge dans le controller enfant
        require_once(ROOT.'src/Model/'.$model.'.php');
        $this->$model = new $model();
    }
    
    public function render(string $ficher, array $options = []){
        $controllerPath = ROOT.'templates/'.strtolower(str_replace('Controller','',get_class($this))).'/'. $ficher.'.php';
        $this->renderBase($controllerPath,$options);
    }
    
    public function renderError(string $ficher, array $options = []){
        $controllerPath = ROOT.'templates/errors/'. $ficher.'.php';
        $this->renderBase($controllerPath,$options);
    }
    
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
        
        require_once(ROOT.'Templates/layouts/base.php');
        
    }
}