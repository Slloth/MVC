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
        // Récupère les valeurs du tableau pour en faire des variables à part entière
        extract($options);
        // Récupère le fichier php de la vue, a partire du nom du controller pour selectionner le bon dossier
        require_once(ROOT.'templates/'.strtolower(str_replace('Controller','',get_class($this))).'/'. $ficher.'.php');
    }

    public function renderError(string $ficher, array $options = []){
        // Récupère les valeurs du tableau pour en faire des variables à part entière
        extract($options);
        require_once(ROOT.'templates/errors/'. $ficher.'.php');
    }

}