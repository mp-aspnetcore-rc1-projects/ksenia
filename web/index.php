<?php
// ROUTER for php built in server
use Symfony\Component\HttpFoundation\Request;

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
return false;

}
/* autoload */
$vendor = require __DIR__.'/../vendor/autoload.php';
$vendor->add('',__DIR__.'/../php');
/* application bootstrap */
$debug = getenv('PHP_ENV')==='production'?false:true;
$app = new \App(array('debug'=>$debug));
Request::enableHttpMethodParameterOverride();
$app['http_cache']->run();