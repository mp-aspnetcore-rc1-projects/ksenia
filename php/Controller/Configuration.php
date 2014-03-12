<?php

namespace Controller;
use \App;
use Doctrine\MongoDB\GridFSFile;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Configuration implements ControllerProviderInterface
{

    /** update configuration settings */
    function update(App $app, Request $req) {
        /** @var \Entity\Configuration $configuration */
        $configuration = $app->configurationService->find();
        if (!$configuration) {
            $app->abort(404);
        }
        $form = $app->formFactory->create(new \Form\Configuration(), $configuration);
        if ('POST' === $req->getMethod()) {
            if ($form->handleRequest($req)->isValid()) {
                $app->configurationService->update($configuration);
                return $app->redirect($app->url_generator->generate('configuration_update'));
            }
        }
        return $app->twig->render('configuration_update', array(
            'configuration' => $configuration, 'form' => $form->createView()));
    }


    /**
     * @inheritdoc
     */
    public function connect(Application $app) {
        /**
         * CONFIGURATION MANAGEMENT
         */
        /** @var \Silex\ControllerCollection $configurationController */
        $configurationController = $app['controllers_factory'];
        $configurationController->match('/update', array($this, 'update'))
            ->bind('configuration_update');
        return $configurationController;
    }
}