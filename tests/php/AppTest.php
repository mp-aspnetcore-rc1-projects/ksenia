<?php

use Entity\Image;
use Entity\Project;
use Service\ImageStub;
use Silex\WebTestCase;

class AppTest extends WebTestCase
{

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernel
     */
    public function createApplication()
    {
        return Bootstrap::getApp();
    }


    /**
     * GET /
     */
    function testIndex()
    {

        $this->app['imageService'] = $this->app->share(function () {
            return new ImageStub();
        });
        $project = new Project();
        $project->setId('bar');
        $image = new Image();
        $image->setDescription("foo");
        $image->setId("bar");
        $image->setExtension('png');
        $image->setBasename("baz");
        $image->setIsPublished(true);
        $image->setProject($project);
        $this->app->imageService->create($image);
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");
        $html = $crawler->html();
        $this->assertContains("Marc Paraiso", $html);
    }

    function test404()
    {
        $client = $this->createClient();
        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $crawler = $client->request("GET", "/foobar");
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}