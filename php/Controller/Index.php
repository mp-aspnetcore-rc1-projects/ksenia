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


class Index implements ControllerProviderInterface
{

    function index(App $app)
    {
        return $app->twig->render('index');
    }

    /**
     * Load an image stored on grid fs
     * @link http://php-and-symfony.matthiasnoback.nl/2012/10/uploading-files-to-mongodb-gridfs-2/
     * @param App $app
     * @param Request $req
     * @param $imageId
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    function imageLoad(App $app, Request $req, $imageId)
    {
        /** @var \Entity\Image $image */
        $image = $app->imageService->find($imageId);
        if (!$image) {
            $app->abort(404);
        }
        return $app->stream(function () use ($image) {
            $file = $image->getFile();
            $r = $file->getMongoGridFSFile()->getResource();
            $out = fopen('php://output', 'w');
            stream_copy_to_stream($r, $out);
            fclose($out);
            fclose($r);
        }, 200, array(
            'Content-Type' => $image->getMimeType() ? $image->getMimeType() : 'image/*',
            'Cache-Control' => 's-maxage=10',
            'Etag' => $image->getMd5()
        ));
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
        /* @var \Silex\ControllerCollection $portfolioController */
        $portfolioController = $app['controllers_factory'];
        $portfolioController->get('/', array($this, 'index'))
            ->bind('index');
        $portfolioController->get('/static/images/{imageId}.{extension}', array($this, 'imageLoad'))
            ->value("extension", "jpg")
            ->bind('image_load');
//@TODO solve image caching problem
//            ->after(function (Request $req, Response $res) use ($app) {
//                /* @var \App $app */
//                if (isset($app['ksenia_cache_images_locally']) && $app['ksenia_cache_images_locally']==true) {
//                    /* @var \Entity\Image $image */
//                    $image = $app->imageService->find($req->attributes->get('imageId'));
//                    $path = $app['ksenia_image_cache_path'] . "/" . $image->getId() . "." . $image->getExtension();
//                    try {
//                        $localImage = fopen($path, 'x+');
//                        $fsImage = $image->getFile()->getMongoGridFSFile()->getResource();
//                        stream_copy_to_stream($fsImage, $localImage);
//                        fclose($fsImage);
//                        fclose($localImage);
//                    } catch (\Exception $e) {
//                        $app->logger->err($e->getMessage());
//                    }
//                }
//            });

        return $portfolioController;
    }
}