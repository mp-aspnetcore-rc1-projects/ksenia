<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
namespace Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Administration implements ControllerProviderInterface
{
    function index(Application $app)
    {
        return $app['twig']->render('admin_index');
    }

    function projectNew(Application $app, Request $req)
    {
        return $app['twig']->render('project_new');
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        /* @var \Silex\ControllerCollection $projectController */
        $projectController = $app['controllers_factory'];
        $projectController->match('/', array($this, 'projectNew'));
        /* @var \Silex\ControllerCollection $adminController */
        $adminController = $app['controllers_factory'];
        $adminController->get('/',array($this,'index'))
            ->bind('admin_index');
        $adminController->mount('/project', $projectController);

        return $adminController;
    }
}