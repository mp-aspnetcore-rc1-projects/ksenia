<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class App
 * @property \Twig_Environment $twig
 * @property \MongoClient $mongo
 * @property string $connection_string
 */
class App extends Application
{
    function __construct(array $params = array())
    {
        parent::__construct($params);
        $this->register(new Config);

    }

    function __get($property){
        if ($this->offsetExists($property)){
            return $this[$property];
        }else{
            throw new \Exception("Property $property doesnt exist");
        }
    }
}

