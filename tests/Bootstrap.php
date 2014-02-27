<?php

$autoload=require __DIR__.'/../vendor/autoload.php';

$autoload->add('',__DIR__);
$autoload->add('',__DIR__.'/../php/');

class Bootstrap{
    static function getApp(){
        $app = new App(array("debug"=>true));
        $app['connection_string'] = getenv('KSENIA_MONGODB_TEST');
        return $app;
    }
}