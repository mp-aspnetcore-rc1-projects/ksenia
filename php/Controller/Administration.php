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
use Entity\Image;
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
    function upload(App $app, Request $req)
    {
        if ($req->isXmlHttpRequest()) {

        }
        return $app->json(array('status' => 200, "message" => "uploaded"));
    }

    function projectIndex(App $app, Request $req)
    {
        $projects = $app->projectService->findAll();
        return $app->twig->render('project_index', array('projects' => $projects));
    }

    /**
     * @route /private/project/new
     * @param App $app
     * @param Request $req
     * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    function projectNew(App $app, Request $req)
    {
        $project = new \Entity\Project();
        $form = $app->formFactory->create(new \Form\Project(), $project);
        if ('POST' === $req->getMethod()) {
            if ($form->handleRequest($req)->isValid()) {
                $app->projectService->insert($project);
                return $app->redirect($app->url_generator->generate('project_read', array('id' => $project->getId())));
            }
        }
        return $app->twig->render('project_new', array('form' => $form->createView()));
    }

    function projectUpdate(App $app, Request $req, $id)
    {
        $project = $app->projectService->find($id);
        $form = $app->formFactory->create(new \Form\Project(), $project);
        if (!$project) {
            return $app->abort(404, 'project not found');
        }
        if ('POST' === $req->getMethod()) {
            if ($form->handleRequest($req)->isValid()) {
                $app->projectService->update($project);
                return $app->redirect($app->url_generator->generate('project_read', array('id' => $project->getId())));
            }
        }
        return $app->twig->render('project_update', array('project' => $project, 'form' => $form->createView()));
    }

    function projectRead(App $app, Request $req, $id)
    {
        $project = $app->projectService->find($id);
        return $app->twig->render('project_read', array('project' => $project));
    }

    function projectDelete(App $app, Request $req, $id)
    {
        $project = $app->projectService->find($id);
        if (!$project) {
            $app->abort(404);
        }
        $app->projectService->delete($project);
        return $app->redirect($app->url_generator->generate('project_index'));
    }

    function projectClone(App $app, Request $req, $id)
    {
        $project = $app->projectService->find($id);
        if (!$project) {
            $app->abort(404);
        }
        /** @var \Entity\Project $newProject */
        $newProject = $project->copy();
        $newProject->setTitle(uniqid($newProject->getTitle() . "_"));
        $app->projectService->insert($newProject);
        return $app->redirect($app->url_generator->generate('project_read', array('id' => $newProject->getId())));
    }

    function imageIndex(App $app, Request $req, $projectId)
    {
        /** @var \Entity\Project $project */
        $project = $app->projectService->find($projectId);
        if (!$project) {
            $app->abort(404);
        }
        return $app->twig->render('image_index', array('project' => $project));
    }

    function imageRead(App $app, Request $req, $projectId, $imageId)
    {
        /** @var \Entity\Project $project */
        $project = $app->projectService->find($projectId);
        if (!$project) {
            $app->abort(404);
        }
        $image = $project->getImageById($imageId);
        return $app->twig->render('image_read', array('project' => $project, 'image' => $image));
    }

    function imageCreate(App $app, Request $req, $projectId)
    {
        $project = $app->projectService->find($projectId);
        if (!$projectId) {
            $app->abort(404, 'project not found');
        }
        $image = new \Entity\Image();
        $image->setProject($project);
        $formType = new \Form\Image();
        $form = $app->formFactory->create($formType, $image);
        if ('POST' === $req->getMethod()) {
            if ($form->handleRequest($req)->isValid()) {
                $project->addImage($image);
                $app->imageService->insert($image);
                $app->projectService->update($project);
                return $app->redirect($app->url_generator->generate('project_read', array('id' => $projectId)));
            }
        }
        return $app->twig->render('image_create', array('project' => $project, 'form' => $form->createView()));

    }

    function imageUpdate(App $app, Request $req, $projectId, $imageId)
    {
        /** @var \Entity\Project $project */
        $project = $app->projectService->find($projectId);
        if (!$projectId) {
            $app->abort(404, 'project not found');
        }
        $image = $project->getImageById($imageId);
        if (!$image) {
            $app->abort(404);
        }
        $form = $app->formFactory->create(new \Form\Image(), $image);
        if ('POST' === $req->getMethod()) {
            if ($form->handleRequest($req)->isValid()) {
                $app->imageService->update($image);
                return $app->redirect($app->url_generator->generate('project_read', array('id' => $projectId)));
            }
        }
        return $app->twig->render('image_update', array(
            'project' => $project, 'form' => $form->createView(), 'image' => $image));

    }

    function imageDelete(App $app, Request $req, $projectId, $imageId)
    {
        /** @var \Entity\Project $project */
        $project = $app->projectService->find($projectId);
        $image = $app->imageService->find($imageId);

        if (!$project || !$image) {
            $app->abort(404);
        }

        if ($project->getImages()->contains($image)) {
            $project->removeImage($image);
            $app->projectService->update($project);
            $app->imageService->delete($image);
            return $app->redirect($app->url_generator->generate('image_index', array('projectId' => $projectId)));
        } else {
            $app->abort(500, 'image not part of project');
        }
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
        /**
         * PROJECT MANAGEMENT
         */
        /* @var \Silex\ControllerCollection $projectController */
        $projectController = $app['controllers_factory'];
        $projectController->get('/', array($this, 'projectIndex'))
            ->bind('project_index');
        $projectController->match('/new', array($this, 'projectNew'))
            ->bind('project_new');
        $projectController->get('/{id}', array($this, 'projectRead'))
            ->bind('project_read');
        $projectController->match('/{id}/update', array($this, 'projectUpdate'))
            ->bind('project_update');
        $projectController->delete('/{id}', array($this, 'projectDelete'))
            ->bind('project_delete');
        $projectController->post('/{id}/clone', array($this, 'projectClone'))
            ->bind('project_clone');

        /**
         * IMAGE MANAGEMENT
         */
        /** @var \Silex\ControllerCollection $imageController */
        $imageController = $app['controllers_factory'];
        $imageController->match('/{projectId}/image', array($this, 'imageIndex'))
            ->bind('image_index');
        $imageController->match('/{projectId}/image/new', array($this, 'imageCreate'))
            ->bind('image_create');
        $projectController->mount('', $imageController);
        $imageController->match('/{projectId}/image/{imageId}/update', array($this, 'imageUpdate'))
            ->bind('image_update');
        $imageController->delete('/{projectId}/image/{imageId}/delete', array($this, 'imageDelete'))
            ->bind('image_delete');
        $imageController->get('/{projectId}/image/{imageId}', array($this, 'imageRead'))
            ->bind('image_read');
        /**
         * ADMINISTRATION
         */
        /* @var \Silex\ControllerCollection $adminController */
        $adminController = $app['controllers_factory'];
        $adminController->get('/', array($this, 'index'))
            ->bind('admin_index');
        $adminController->post('/upload', array($this, 'upload'));
        $adminController->mount('/project', $projectController);

        return $adminController;
    }
}