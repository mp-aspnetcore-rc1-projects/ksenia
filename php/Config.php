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
use Mparaiso\SimpleRest\Controller\Controller as RestController;
use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SerializerServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\ServiceProviderInterface;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Configure application
 */
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
    public function register(Application $app) {
        /**
         * constants
         */
        $app['ksu_connection_string'] = getenv('KSENIA_MONGODB');
        $app['ksu_dbname'] = "ksenia-portfolio";
        $app['ksu_cache_images_locally'] = true;
        $app['ksu_image_cache_path'] = __DIR__ . "/../web/static/images/cache/";
        $app['temp'] = __DIR__ . "/../temp";
        /* hard coded configuration */
        $app['ksu'] = array(
            'title' => "KSENIA PIROVSKIKH",
            'subtitle'=>'Interior and Graphic Designer',
            "version"=>"0.0.1",
            'template' => 'html5',
        );
        $app['settings']=$app->share(function($app){
            return $app->configurationService->find();
        });

        /**
         * silex core services
         */
        $app->register(new ServiceControllerServiceProvider);
        $app->register(new SessionServiceProvider);
        /*
        $app->register(new SecurityServiceProvider(),array(
            'security.firewalls'=>array(
                "secured"=>array()
            )
        ));
        */
        $app->register(new SerializerServiceProvider());
        $app->register(new MonologServiceProvider, array(
            'monolog.logfile' => $app['temp'] . '/' . date('Y-m-d') . '.txt'
        ));
        $app->register(new TwigServiceProvider(), array(
            'twig.path' => array(__DIR__ . '/../templates/' . $app['ksu']['template']),
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
            'odm.connection.server' => function ($app) {
                return $app['ksu_connection_string'];
            },
            'odm.connection.dbname' => function ($app) {
                return $app['ksu_dbname'];
            },
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
            return new \Service\Image($app['odm.dm'], $app['projectService']);
        });
        $app['pageService'] = $app->share(function ($app) {
            return new \Service\Page($app['odm.dm']);
        });
        $app['menuService'] = $app->share(function ($app) {
            return new \Service\Menu($app['odm.dm']);
        });
        $app['linkService'] = $app->share(function ($app) {
            return new \Service\Link($app['odm.dm']);
        });
        $app['configurationService']=$app->share(function($app){
            return new \Service\Configuration($app['odm.dm']);
        });
        /** REST CONTROLLERS */
        $app['imageRestController'] = $app->share(function ($app) {
            return new RestController(array(
                "debug" => $app['debug'],
                "resource" => "image",
                "resourcePluralize" => "images",
                "model" => '\Entity\Image',
                "service" => $app["imageService"],
                "criteria" => array('project','language'),
                "logger"=>$app['logger']
            ));
        });
        $app['projectRestController'] = $app->share(function ($app) {
            return new RestController(array(
                "debug" => $app['debug'],
                "resource" => "project",
                "resourcePluralize" => "projects",
                "model" => '\Entity\Project',
                "service" => $app["projectService"],
                "logger" => $app['logger'],
                "allows" => array('list', 'read'),
                "criteria"=>array('language')
            ));
        });
        $app['pageRestController'] = $app->share(function ($app) {
            return new RestController(array(
                "debug" => $app['debug'],
                "resource" => "page",
                "resourcePluralize" => "pages",
                "model" => '\Entity\Page',
                "service" => $app['pageService'],
                "logger" => $app['logger'],
                "allows" => array('list', 'read'),
                'criteria'=>array('language')
            ));
        });
        $app['menuRestController'] = $app->share(function ($app) {
            return new RestController(array(
                "debug" => $app['debug'],
                "resource" => "menu",
                "resourcePluralize" => "menus",
                "model" => '\Entity\Menu',
                "service" => $app['menuService'],
                "logger" => $app['logger'],
                "allows" => array('list', 'read'),
                "criteria"=>array('isMain','language')
            ));
        });

    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app) {
        /**
         * routing
         */
        $app->mount('/private', new Administration());
        $app->mount('/private/api/', $app['imageRestController']);
        $app->mount('/private/api/', $app['projectRestController']);
        $app->mount('/private/api/', $app['pageRestController']);
        $app->mount('/private/api/', $app['menuRestController']);
        $app->mount('/', new Index());
    }
}



