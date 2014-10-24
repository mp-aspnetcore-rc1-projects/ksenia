<?php

namespace Controller;

use Silex\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernel;

class IndexTest extends WebTestCase
{

    /**
     * Creates the application.
     *
     * @return HttpKernel
     */
    public function createApplication()
    {
        return \Bootstrap::getApp();
    }

    /**
     * GET /project/{projectId}/image/{imageId}/{title}
     */
    function testProjectImageTitle()
    {
        /** @var \App $app */
        $app = $this->app;
        $url = $app->url_generator->generate('project_image_title', array('projectId' => 100, 'imageId' => 200,
            'title' => "foo"));
        $this->assertNotNull($url);
        /// $client = $this->createClient();
    }
}
