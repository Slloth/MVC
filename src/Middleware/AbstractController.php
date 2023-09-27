<?php

//Permet a tout les controllers qui en hérite d'avoir les méthodes basiques
#[AllowDynamicProperties]
abstract class AbstractController{
    public function loadModel(string $model){
        //récupère le nom d'un modèle et le charge dans le controller enfant
        require_once(ROOT.'src/Model/'.$model.'.php');
        $this->$model = new $model();
    }
}