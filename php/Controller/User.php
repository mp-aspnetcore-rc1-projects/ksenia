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

/**
 * Class User
 * User related actions in administration
 * @package Controller
 */
class User implements ControllerProviderInterface
{
    function profile(App $app) {
        return $app->twig->render('user_profile');
    }

    function logout(App $app) {
    }


    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app) {

        $userController = $app['controllers_factory'];
        /* @var \Silex\ControllerCollection $userController */
        $userController->match('/profile', array($this, 'profile'))
            ->bind("mp.user.route.profile.index");
        $userController->match('/logout', array($this, 'logout'))
            ->bind('mp.user.route.logout');
        return $userController;

    }
}