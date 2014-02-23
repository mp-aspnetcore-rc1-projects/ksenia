<?php

$vendor=require __DIR__.'/vendor/autoload.php';

use Silex\Application;

$app = new Application();

$app->get('/',function(Application $app){
       return "hello world";
});

$app->run();