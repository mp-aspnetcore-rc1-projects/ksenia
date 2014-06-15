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
use Symfony\Component\HttpFoundation\Response;


class Menu implements ControllerProviderInterface
{
    function index(App $app, Request $req) {
        $menus = $app->menuService->findAll();
        return $app->twig->render('menu_index', array('menus' => $menus));
    }

    function read(App $app, Request $req, $id) {
        $menu = $app->menuService->find($id);
        if (!$menu) $app->abort(404);
        return $app->twig->render('menu_read', array('menu' => $menu));
    }

    function create(App $app, Request $req) {
        $menu = new \Entity\Menu;
        $form = $app->formFactory->create(new \Form\Menu, $menu);
        if ($req->getMethod() == "POST") {
            if ($form->handleRequest($req)->isValid()) {
                $app->menuService->create($menu);
                return $app->redirect($app->url_generator->generate('menu_index'));
            }
        }
        return $app->twig->render('menu_create', array('mnenu'=>$menu,'form' => $form->createView()));
    }

    function update(App $app, Request $req, $id) {

        $menu = $app->menuService->find($id);
        if (!$menu) $app->abort(404);
        $form = $app->formFactory->create(new \Form\Menu, $menu);
        if ($req->getMethod() == "POST") {
            if ($form->handleRequest($req)->isValid()) {
                $app->menuService->update($menu);
                return $app->redirect($app->url_generator->generate('menu_index'));
            }
        }
        return $app->twig->render('menu_update', array('menu' => $menu, 'form' => $form->createView()));

    }

    function delete(App $app, Request $req, $id) {

    }

    /**
     * @inheritdoc
     */
    public function connect(Application $app) {
        /* @var \Silex\ControllerCollection $menuCtrl */
        $menuCtrl = $app['controllers_factory'];
        $menuCtrl->get('/', array($this, 'index'))
            ->bind('menu_index');
        $menuCtrl->match('/create', array($this, 'create'))
            ->bind('menu_create');
        $menuCtrl->get('/{id}', array($this, 'read'))
            ->bind('menu_read');
        $menuCtrl->match('/{id}/update', array($this, 'update'))
            ->bind('menu_update');
        $menuCtrl->delete('/{id}/delete', array($this, 'delete'))
            ->bind('menu_delete');
        return $menuCtrl;
    }
}