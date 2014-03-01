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
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Page implements ControllerProviderInterface
{
    function pageIndex(App $app)
    {
        $pages = $app->pageService->findAll();
        return $app->twig->render('page_index', array('pages' => $pages));
    }

    /**
     * @route /private/page/create
     * @param App $app
     * @param Request $req
     * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    function pageCreate(App $app, Request $req)
    {
        $page = new \Entity\Page();
        $form = $app->formFactory->create(new \Form\Page(), $page);
        if ('POST' === $req->getMethod()) {
            if ($form->handleRequest($req)->isValid()) {
                $app->pageService->insert($page);
                return $app->redirect($app->url_generator->generate('page_read', array('id' => $page->getId())));
            }
        }
        return $app->twig->render('page_create', array('form' => $form->createView()));
    }

    function pageUpdate(App $app, Request $req, $id)
    {
        $page = $app->pageService->find($id);
        $form = $app->formFactory->create(new \Form\Page(), $page);
        if (!$page) {
            return $app->abort(404, 'page not found');
        }
        if ('POST' === $req->getMethod()) {
            if ($form->handleRequest($req)->isValid()) {
                $app->pageService->update($page);
                return $app->redirect($app->url_generator->generate('page_read', array('id' => $page->getId())));
            }
        }
        return $app->twig->render('page_update', array('page' => $page, 'form' => $form->createView()));
    }

    function pageRead(App $app, $id)
    {
        $page = $app->pageService->find($id);
        return $app->twig->render('page_read', array('page' => $page));
    }

    function pageDelete(App $app, $id)
    {
        $page = $app->pageService->find($id);
        if (!$page) {
            $app->abort(404);
        }
        $app->pageService->delete($page);
        return $app->redirect($app->url_generator->generate('page_index'));
    }

    function pageClone(App $app, $id)
    {
        $page = $app->pageService->find($id);
        if (!$page) {
            $app->abort(404);
        }
        /** @var \Entity\Page $newPage */
        $newPage = $page->copy();
        $newPage->setTitle(uniqid($newPage->getTitle() . "_"));
        $app->pageService->insert($newPage);
        return $app->redirect($app->url_generator->generate('page_read', array('id' => $newPage->getId())));
    }


    /**
     * @inheritdoc
     */
    public function connect(Application $app)
    {
        /**
         * PAGE MANAGEMENT
         */
        /* @var \Silex\ControllerCollection $pageController */
        $pageController = $app['controllers_factory'];
        $pageController->get('/', array($this, 'pageIndex'))
            ->bind('page_index');
        $pageController->match('/create', array($this, 'pageCreate'))
            ->bind('page_create');
        $pageController->get('/{id}', array($this, 'pageRead'))
            ->bind('page_read');
        $pageController->match('/{id}/update', array($this, 'pageUpdate'))
            ->bind('page_update');
        $pageController->delete('/{id}', array($this, 'pageDelete'))
            ->bind('page_delete');
        $pageController->post('/{id}/clone', array($this, 'pageClone'))
            ->bind('page_clone');

        return $pageController;
    }
}