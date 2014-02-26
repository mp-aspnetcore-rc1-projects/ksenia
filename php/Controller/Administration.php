<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
namespace Controller;

use App;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Administration implements ControllerProviderInterface
{
    function index(App $app)
    {
        return $app->twig->render('admin_index');
    }

    /**
     * Upload a file, send json response
     * @param App $app
     * @param Request $req
     */
    function upload(App $app,Request $req){
        if($req->isXmlHttpRequest()){

        }
        return $app->json(array('status'=>200,"message"=>"uploaded"));
    }

    function projectIndex(App $app,Request $req){
        return $app->twig->render('project_index');
    }

    function projectNew(App $app, Request $req)
    {
        return $app->twig->render('project_new');
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
        $projectController->get('/',array($this,'projectIndex'))
            ->bind('project_index');
        $projectController->match('/new', array($this, 'projectNew'))
            ->bind('project_new');
        /* @var \Silex\ControllerCollection $adminController */
        $adminController = $app['controllers_factory'];
        $adminController->get('/',array($this,'index'))
            ->bind('admin_index');
        $adminController->post('/upload',array($this,'upload'));
        $adminController->mount('/project', $projectController);

        return $adminController;
    }
}