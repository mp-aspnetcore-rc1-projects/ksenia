<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
namespace Controller;

use App;
use Doctrine\Common\Collections\Criteria;
use Doctrine\MongoDB\GridFSFile;
use Entity\Image;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Administration implements ControllerProviderInterface
{
    function index(App $app) {
        return $app->twig->render('admin_index');
    }


    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app) {

        $projectController = new Project;
        /**
         * ADMINISTRATION
         */
        /* @var \Silex\ControllerCollection $adminController */
        $adminController = $app['controllers_factory'];
        $adminController->get('/', array($this, 'index'))
            ->bind('admin_index');
        $adminController->mount('/project', $projectController->connect($app));
        $page = new Page();
        $adminController->mount('/page', $page->connect($app));
        $menu = new Menu();
        $adminController->mount('/menu', $menu->connect($app));
        $configuration = new Configuration();
        $adminController->mount('/configuration', $configuration->connect($app));
        $user = new User();
        $adminController->mount('/user', $user->connect($app));
        return $adminController;
    }
}