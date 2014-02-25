<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
use Controller\Administration;
use Controller\Portfolio;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\ServiceProviderInterface;

class Config implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        /**
         * constants
         */
        $app['connection_string'] = getenv('KSENIA_MONGODB');
        $app['temp'] = getenv('TMP');
        $app['title'] = "ksenia - porfolio";

        /**
         * third party services
         */
        $app->register(new ServiceControllerServiceProvider);
        $app->register(new SessionServiceProvider);
        $app->register(new TwigServiceProvider(), array(
            'twig.templates' => require(__DIR__ . '/templates.php'),
            'twig.options' => array('cache' => $app['temp'] . '/twig'
            )));
        /**
         * custom services
         */
        /**
         * mongodb connection
         */
        $app['mongo'] = $app->share(function (Application $app) {
            $mongo = new Mongo($app['connection_string']);
            return $mongo;
        });
        /**
         * routing
         */
        $app->mount('/private', new Administration());
        $app->mount('/', new Portfolio());
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
// TODO: Implement boot() method.
    }
}



