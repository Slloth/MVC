<?php

namespace App\Controllers;

use Core\Controller\AbstractController;

class HomeController extends AbstractController{
    public function index(){

        $title = "Je suis le controlleur Home";
        $this->render("index",compact('title'));
    }
}