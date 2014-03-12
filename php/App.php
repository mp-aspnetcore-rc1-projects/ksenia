<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Generator\UrlGenerator;
/**
 * Class App
 * @property \Twig_Environment $twig
 * @property \MongoClient $mongo
 * @property string $connection_string
 * @property FormFactory formFactory
 * @property UrlGenerator url_generator
 * @property  \Service\Project projectService
 * @property \Service\Page pageService
 * @property \Service\Image imageService
 * @property \Symfony\Component\Serializer\Serializer serializer
 * @property \Monolog\Logger logger
 * @property \Service\Menu menuService
 * @property \Service\Link linkService
 * @property \Service\Configuration configurationService
 * @property array ksu
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

