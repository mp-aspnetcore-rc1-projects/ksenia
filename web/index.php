<?php
/**
 * Phalcon micro sample application
 *
 * @category SAMPLE_APP
 * @package  App
 * @author   mparaiso <mparaiso@online.fr>
 * @license  MIT http://mit.com
 * @link     mylink.com
 */

spl_autoload_register();

use Controllers\RobotsController;
use Model\Robots;
use Phalcon\Http\Response;
use Phalcon\Mvc\Micro;

$app = new Micro();

RobotsController::connect($app);

$app->handle();

/*
use Phalcon\Http\Response;
use Phalcon\Mvc\Micro;

$app = new Micro();

// @phalcon Not-Found Handler
$app->notFound(function()use($app){
    $app->response->setStatusCode(404,"Not Found")->sendHeaders();
    echo "This is nuts, i cant find that page !";
});

// @phalcon route handlers
$app->get('/', function(){
    $res = new Response;
    $res->setContent("Hello World!!!");
    return $res;
});

$app->get('/foo/{bar}',function($bar){
    echo "bar = $bar";
});

/*
$app->get('/say/hello/{name}', "say_hello");

// With a static method
$app->get('/say/hello/{name}', "SomeClass::someSayMethod");

// With a method in an object
//$myController = new MyController();
//$app->get('/say/hello/{name}', array($myController, "someAction"));

//Anonymous function
$app->get('/say/hello/{name}', function ($name) {
    echo "<h1>Hello! $name</h1>";
});
 
$app->handle();

 */
