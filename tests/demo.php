<?php
require __DIR__ . '/../src/Ela.php';

use Ela\Di\Di;
use Ela\Application\Web\Application;
use Ela\Config;



$app = Application::create(__DIR__ .'/config.ini');
$app->setDi(new Di());
$app->run();

