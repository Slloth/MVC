<?php

use Core\Autoloader;
use Core\DotEnvEnvironment;
use Core\Main;

define('ROOT', dirname(__DIR__).'/');

require_once ROOT."Autoloader.php";
Autoloader::register();
//On charge les variables d'environemments
DotEnvEnvironment::dotEnvLoad();

$app = new Main;

$app->start();