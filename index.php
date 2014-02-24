<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code is open sourced for educational purpose only.
 */
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$vendor = require __DIR__.'/vendor/autoload.php';

$app = new Application(array('debug'=>true));
// constants
$app['mongo_connection_string'] = getenv('KSENIA_MONGODB');
// third party services 
$app->register(new ServiceControllerServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new TwigServiceProvider(array('twig.templates'=>require(__DIR__.'/templates'))));

/**
 * ADMINISTRATION
 * @var RouteCollection
 */
$adminController = $app['controllers_factory'];
$adminController->all('/upload',function(Application $app,Request $req){

})->bind('admin_upload');
/**
 * HOMEPAGE
 */
$app->all('/',function(){
	return $twig->render("index");
});
$app->mount('/private',$adminController);

$app->run();