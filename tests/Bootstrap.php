<?php

$autoload=require __DIR__.'/../vendor/autoload.php';

$autoload->add('',__DIR__);
$autoload->add('',__DIR__.'/../php/');

class Bootstrap{
    static function getApp(){
        $app = new App(array("debug"=>true));
        $app['ksu_connection_string'] = getenv('KSENIA_MONGODB_TEST');
        $app['ksu_dbname'] = 'tests';
        $app->boot();
        return $app;
    }
}