<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
use Controller\Administration;
use Controller\Index;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Mparaiso\Provider\ConsoleServiceProvider;
use Mparaiso\Provider\DoctrineODMMongoDBServiceProvider;
use Mparaiso\Provider\SimpleUserServiceProvider;
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
use Silex\Provider\ValidatorServiceProvider;
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
    public function register(Application $app)
    {
        /**
         * constants
         */
        $app['ksu_connection_string'] = $app->share(function () {
            return getenv('KSENIA_MONGODB');
        });
        $app['ksu_dbname'] = "ksenia-portfolio";
        $app['ksu_cache_images_locally'] = true;
        $app['ksu_image_cache_path'] = __DIR__ . "/../web/static/images/cache/";
        $app['temp'] = __DIR__ . "/../temp";
        /* hard coded configuration */
        $app['ksu'] = array(
            "version" => "0.0.1",
            'template' => 'html5'
        );
        $app['settings'] = $app->share(function ($app) {
            return $app->configurationService->find();
        });

        /**
         * silex core services
         */
        $app->register(new ServiceControllerServiceProvider);
        $app->register(new SessionServiceProvider, array('session.storage.options' => array('name' => "EXPRESS")));

        $app->register(new SerializerServiceProvider());
        $app->register(new MonologServiceProvider, array(
            'monolog.logfile' => $app['temp'] . '/' . date('Y-m-d') . '.txt',
        ));
        /*
        $app['monolog'] = $app->extend('monolog', function (Logger $monolog, $container) {
            $monolog->pushHandler(new SyslogHandler($monolog->getName(), LOG_USER, Logger::ERROR, true));
            return $monolog;
        });
        */
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
        $app->register(new SimpleUserServiceProvider, array(
            'mp.user.service.user' => $app->share(function ($app) {
                    return $app['userService'];
                }),
            'mp.user.login_template' => 'mp.user.template.login',
            'mp.user.template.layout' => "mp.user.template.layout",
            'mp.user.user.class' => '\Entity\User',
            "mp.user.om" => $app->share(function ($app) {
                    return $app['odm.om'];
                }),
            "mp.user.manager_registry" => $app->share(function ($app) {
                    return $app['odm.manager_registry'];
                }),
            'mp.user.allow_registration' => false
        ));
        $app->register(new SecurityServiceProvider, array(
            "security.role_hierarchy" => array(
                'ROLE_USER' => array(),
                'ROLE_ADMIN' => array('ROLE_USER')),
            "security.access_rules" => array(
                array('/login', 'IS_AUTHENTICATED_ANONYMOUSLY'),
                array('/logout', 'IS_AUTHENTICATED_FULLY'),
                array('/login-check', 'IS_AUTHENTICATED_FULLY'),
                array('/private', 'IS_AUTHENTICATED_FULLY'),
            ),
            "security.firewalls" => $app->share(function (App $app) {
                    return array(
                        "secured" => array(
                            "pattern" => "^/",
                            "anonymous" => TRUE,
                            "form" => array(
                                "login_path" => "/login",
                                "check_path" => "/login-check"
                            ),
                            "logout" => array(
                                "logout_path" => $app->url_generator->generate('mp.user.route.logout'),
                                "target" => "/",
                                "invalidate_session" => true,
                                "delete_cookies" => true
                            ),
                            "users" => $app['mp.user.user_provider']
                        )
                    );
                })
        ));
        $app->register(new ConsoleServiceProvider(), array());
        $app->register(new ValidatorServiceProvider(), array());
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
        $app['configurationService'] = $app->share(function ($app) {
            return new \Service\Configuration($app['odm.dm']);
        });
        $app['userService'] = $app->share(function ($app) {
            return new \Service\User($app['odm.dm'], $app["security.encoder_factory"]);
        });
        /** REST CONTROLLERS */
        $app['imageRestController'] = $app->share(function ($app) {
            return new RestController(array(
                "debug" => $app['debug'],
                "resource" => "image",
                "resourcePluralize" => "images",
                "model" => '\Entity\Image',
                "service" => $app["imageService"],
                "criteria" => array('project', 'language'),
                "logger" => $app['logger']
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
                "criteria" => array('language')
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
                'criteria' => array('language')
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
                "criteria" => array('isMain', 'language')
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
    public function boot(Application $app)
    {
        /**
         * routing
         */
        /** users */
        $app->match('/login', 'mp.user.controller.security:login')
            ->bind('mp.user.route.login');
        $app->match('/register')
            ->bind('mp.user.route.register');
        $app->match('/login-check')
            ->bind('mp.user.route.login.check');
        /**  */
        $app->mount('/private', new Administration());
        $app->mount('/private/api/', $app['imageRestController']);
        $app->mount('/private/api/', $app['projectRestController']);
        $app->mount('/private/api/', $app['pageRestController']);
        $app->mount('/private/api/', $app['menuRestController']);
        $app->mount('/', new Index());
        /** handle production errors */
        $app->error(function (\Exception $e, $code) use ($app) {
            /* @var App $app */
            // if production show custom error page
            error_log(var_export(array($e->getCode(), $e->getMessage()), true));
            if (!$app['debug']) {
                switch ($code) {
                    case 404:
                        $message = 'The requested page could not be found.';
                        break;
                    default:
                        $message = 'We are sorry, but something went terribly wrong.';
                }
                switch ($app->request->getRequestFormat('html')) {
                    case 'json':
                        $response = $app->json(array('message' => $message));
                        break;
                    default:
                        $response = $app->twig->render('error', array('message' => $message));
                        $response = new Response($response, $code);
                }
                return $response;
            }
        });
    }
}



