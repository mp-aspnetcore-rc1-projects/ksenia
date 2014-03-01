<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
use Controller\Administration;
use Controller\Index;
use Mparaiso\Provider\DoctrineODMMongoDBServiceProvider;
use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\ServiceProviderInterface;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $app['ksenia_connection_string'] = getenv('KSENIA_MONGODB');
        $app['ksenia_dbname'] = "ksenia-portfolio";
        $app['temp'] =  __DIR__ . "/../temp";
        $app['title'] = "ksenia - porfolio";

        /**
         * silex core services
         */
        $app->register(new ServiceControllerServiceProvider);
        $app->register(new SessionServiceProvider);
        $app->register(new \Silex\Provider\MonologServiceProvider,array(
            'monolog.logfile'=>$app['temp'].'/'.date('Y-m-d').'.txt'
        ));
        $app->register(new TwigServiceProvider(), array(
            'twig.templates' => require(__DIR__ . '/Views/templates.php'),
            'twig.options' => array('cache' => $app['temp'] . '/twig'
            )));
        $app->register(new TranslationServiceProvider);
        $app->register(new UrlGeneratorServiceProvider);
        $app->register(new FormServiceProvider);
        $app->register(new HttpCacheServiceProvider(), array(
            'http_cache.cache_dir' => $app['temp'] . "/cache/"
        ));
        /**
         * custom services
         */
        /** form factory helper */
        $app->register(new  DoctrineODMMongoDBServiceProvider(), array(
            'odm.connection.server' => $app['ksenia_connection_string'],
            'odm.connection.dbname' => $app['ksenia_dbname'],
            'odm.connection.options' => array('connect' => true),
            'odm.proxy_dir' => __DIR__ . '/Proxy',
            'odm.hydrator_dir' => __DIR__ . '/Hydrator',
            'odm.driver.configs' => array(
                'default' => array(
                    'namespace' => 'Entity',
                    'path' => __DIR__ . '/Entity',
                    'type' => 'annotations'
                )
            )
        ));
        $app['formFactory'] = $app->share(function ($app) {
            return $app['form.factory'];
        });
        /**
         * mongodb connection
         */
        $app['mongo'] = $app->share(function (App $app) {
            $mongo = new MongoClient($app->connection_string);
            return $mongo;
        });
        /**
         * project
         */
        $app['projectService'] = $app->share(function ($app) {
            return new \Service\Project($app['odm.dm']);
        });
        $app['imageService'] = $app->share(function ($app) {
            return new \Service\Image($app['odm.dm']);
        });
        $app['pageService'] = $app->share(function ($app) {
            return new \Service\Page($app['odm.dm']);
        });
        /**
         * routing
         */
        $app->mount('/private', new Administration());
        $app->mount('/', new Index());
        $app->after(function (Request $req, Response $res) {
            header_remove("X-Powered-By");
            return $res;
        });
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



