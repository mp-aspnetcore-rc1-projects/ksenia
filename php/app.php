<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;


class App extends Application implements ServiceProviderInterface
{
    function __construct(array $params = array())
    {
        parent::__construct($params);
        $this->register(new Config);

    }
}

