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

$vendor = require __DIR__.'/../vendor/autoload.php';

$debug = getenv('PHP_ENV')==='production'?false:true;

$app = new Application(array('debug'=>$debug));
// constants
$app['mongo_connection_string'] = getenv('KSENIA_MONGODB');
$app['temp'] = getenv('TMP');
$app['title'] = "ksenia - porfolio";
// third party services 
$app->register(new ServiceControllerServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new TwigServiceProvider(),array(
	'twig.templates'=>require(__DIR__.'/templates.php'),
	'twig.options'=>array('cache'=>$app['temp'].'/twig'
)));

/**
 * ADMINISTRATION
 * @var RouteCollection
 */
$adminController = $app['controllers_factory'];
/**
 * manage file upload
 */
$adminController->match('/upload',function(Application $app,Request $req){
	return $app['twig']->render('admin_upload');
})->bind('admin_upload');
/**
 * homepage
 */
$app->match('/',function(Application $app){
	return $app['twig']->render("index");
});
/**
 * mount administration controller
 */
$app->mount('/private',$adminController);

return $app;