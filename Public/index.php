<?php

use Core\Autoloader;
use Core\DotEnvEnvironment;
use Core\Main;

define('ROOT', dirname(__DIR__).'/');

require_once ROOT."Autoloader.php";
Autoloader::register();
//On charge les variables d'environemments
DotEnvEnvironment::dotEnvLoad();

date_default_timezone_set($_ENV["TIMEZONE"]);

$app = new Main;

$app->start();