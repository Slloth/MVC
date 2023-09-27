<?php
class HomeController extends AbstractController {

    public function index(){
        $title = "le titre de la page";
        $this->render('index',compact("title"));
    }
}