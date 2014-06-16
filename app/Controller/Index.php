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
use Doctrine\Common\Collections\ArrayCollection;

class Index implements ControllerProviderInterface
{

    function index(App $app)
    {
        $images = $app->imageService->findAllPublishedImages();
        $image = $images[0];
        return $app->twig->render('index.twig', array("image" => $image, "images" => $images));
    }

    function image(App $app, $imageId)
    {
        $image = $app->imageService->find($imageId);
        return $app->twig->render('index.twig', array('image' => $image));
    }

    function project(App $app, $projectId, $imageId)
    {
        /** @var \Entity\Project $project */
        $project = $app->projectService->find($projectId) or $app->abort(404);

        $image = $project->getImageById($imageId);
        if ($image == null) {
            $image = $project->getImages()->first() or $app->abort(404);
        }
        return $app->twig->render('project.twig', array('image' => $image, 'project' => $project));
    }

    function page(App $app, $pageId)
    {
        $page = $app->pageService->find($pageId) or $app->abort(404);
        return $app->twig->render('page.twig', array('page' => $page));
    }

    /**
     * Returns all menu serialized
     * @return Response  json or xml response
     */
    function publicMenuResource(App $app)
    {
        return $app->json($app->menuService->findAllPublishedMenus());
    }

    function publicImageResource(App $app)
    {
        return $app->json($app->imageService->findAllPublishedImages());
    }

    function publicProjectResource(App $app)
    {
        $projects = $app->projectService->findAllPublishedProjects();
        return $app->json($projects);
    }

    function publicPageResource(App $app)
    {
        $pages = $app->pageService->findAllPublishedPages();
        return $app->json($pages);
    }


    /**
     * Load an image stored on grid fs if the image is not cached
     * @link http://php-and-symfony.matthiasnoback.nl/2012/10/uploading-files-to-mongodb-gridfs-2/
     * @param App $app
     * @param Request $req
     * @param         $imageId
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    function imageLoad(App $app, Request $req, $imageId, $extension)
    {
        /** @var \Entity\Image $image */
        $image = $app->imageService->find($imageId);
        if (!$image) $app->abort(404);
        $dir = $app['ksu_image_cache_path'];
        $doCache = $app['ksu_cache_images_locally'];
        $r = $app->stream(function () use ($image, $imageId, $dir, $doCache) {
            $file = $image->getFile();
            $r = $file->getMongoGridFSFile()->getResource();
            $out = fopen('php://output', 'w');
            if ($doCache) $cache = fopen("$dir/$imageId." . $image->getExtension(), "w");
            while (!feof($r)) {
                $packet = fread($r, 8192);
                fputs($out, $packet);
                //write in cache
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
    public function connect(Application $app)
    {
        /* @var \Silex\ControllerCollection $portfolioController */
        $portfolioController = $app['controllers_factory'];
        $portfolioController->get('/', array($this, 'index'))
            ->bind('index');
        $portfolioController->get('/image/{imageId}/{title}', array($this, 'image'))
            ->value('title', null);
        $portfolioController->get('/project/{projectId}/{title}', array($this, 'project'))
            ->value('title', null)
            ->value('imageId', null)
            ->bind('projectImage')
            ->bind('project');
        $portfolioController->get('/project/{projectId}/image/{imageId}', array($this, 'project'))
            ->bind('image');
        $portfolioController->get('/page/{pageId}/{title}', array($this, 'page'))
            ->value('title', '')
            ->bind('page');
        $portfolioController->get('/static/images/cache/{imageId}.{extension}', array($this, 'imageLoad'))->value("extension", "jpg")
            ->bind('image_load');
        $portfolioController->get('/static/images/cache/flush/{imageId}.{extension}', array($this, 'imageLoad'))
            ->value("extension", "jpg")
            ->bind('image_load_flush');
        $portfolioController->get('/api/project', array($this, 'publicProjectResource'))
            ->bind('public_project_resource');
        $portfolioController->get('/api/page', array($this, 'publicPageResource'))
            ->bind('public_page_resource');
        $portfolioController->get('/api/menu', array($this, 'publicMenuResource'))
            ->bind('public_menu_resource');
        $portfolioController->get('/api/image', array($this, 'publicImageResource'))
            ->bind('public_image_resource');
        return $portfolioController;
    }
}
