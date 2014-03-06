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

    function index(App $app) {
        $projects = $app->projectService->findBy(array('isPublished' => true),array('createdAt'=>-1));
        return $app->twig->render('index.twig', array('projects' => $projects));
    }

    /**
     * Load an image stored on grid fs
     * @link http://php-and-symfony.matthiasnoback.nl/2012/10/uploading-files-to-mongodb-gridfs-2/
     * @param App     $app
     * @param Request $req
     * @param         $imageId
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    function imageLoad(App $app, Request $req, $imageId, $extension) {
        /** @var \Entity\Image $image */
        $image = $app->imageService->find($imageId);
        if (!$image) $app->abort(404);
        $dir = $app['ksenia_image_cache_path'];
        $doCache = $app['ksenia_cache_images_locally'];
        $r = $app->stream(function () use ($image, $imageId, $dir, $doCache) {
            $file = $image->getFile();
            $r = $file->getMongoGridFSFile()->getResource();
            $out = fopen('php://output', 'w');
            if ($doCache) $cache = fopen("$dir/$imageId." . $image->getExtension(), "w");
            while (!feof($r)) {
                $packet = fread($r, 8192);
                fputs($out, $packet);
                if ($doCache) fputs($cache, $packet);
                ob_flush();
                sleep(0.1);
            }
            fclose($r);
            fclose($out);
            if ($doCache) fclose($cache);
        }, 200, array(
            'Content-Type' => $image->getMimeType() ? $image->getMimeType() : 'image/*',
            'Cache-Control' => 's-maxage=20',
            'Etag' => '"' . $image->getMd5() . '"'
        ));
        $r->setTtl(20);
        return $r;
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app) {
        /* @var \Silex\ControllerCollection $portfolioController */
        $portfolioController = $app['controllers_factory'];
        $portfolioController->get('/', array($this, 'index'))
            ->bind('index');
        $portfolioController->get('/static/images/cache/{imageId}.{extension}', array($this, 'imageLoad'))
            ->value("extension", "jpg")
            ->bind('image_load');
        $portfolioController->get('/static/images/cache/flush/{imageId}.{extension}', array($this, 'imageLoad'))
            ->value("extension", "jpg")
            ->bind('image_load_flush');
        return $portfolioController;
    }
}