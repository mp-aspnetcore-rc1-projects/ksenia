<?php

namespace Controller;


use Silex\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernel;

class AdministrationTest extends WebTestCase
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
     * GET /private
     */
    function testPrivate()
    {
        $client = $this->createClient();
        $client->request("GET", "/private");
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }
}