<?php
// ROUTER for php built in server
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;

//php builtin server if file,serve it
$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}
/* autoload */
$vendor = require __DIR__ . '/../vendor/autoload.php';
$vendor->add('', __DIR__ . '/../app');
/* application bootstrap */
$debug = getenv('PHP_ENV') === 'production' ? false : true;
$app = new \App(array('debug' => $debug));
//enable forms to send _PUT or _DELETE requests
Request::enableHttpMethodParameterOverride();
//Catch all errors and convert them to Exceptions
ExceptionHandler::register($debug);
//run tha app
$app['http_cache']->run();
