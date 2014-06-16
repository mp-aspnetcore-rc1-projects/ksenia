<?php

namespace Service;
use Silex\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Class MenuTest
 * @package Service
 * @test \Service\Menu
 * @property \App $app
 */
class MenuTest extends WebTestCase
{


    /**
     * Creates the application.
     *
     * @return HttpKernel
     */
    public function createApplication() {
        return \Bootstrap::getApp();
    }

    public function setUp() {
        parent::setUp();
        $this->app->menuService->getDm()->getDocumentCollection('\Entity\Menu')->drop();
    }

    protected function tearDown() {
        parent::tearDown();
        $this->app->menuService->getDm()->getDocumentCollection('\Entity\Menu')->drop();
    }


    public function testMarkAsMain() {
        $menu1 = new \Entity\Menu;
        $menu1->setIsMain(true)->setTitle('menu-1');
        $this->app->menuService->create($menu1);

        $menu2 = new \Entity\Menu;
        $menu2->setIsMain(true)->setTitle('menu-2');
        $this->app->menuService->create($menu2);

        /** @var \Entity\Menu $menu1 */
        $menu1 = $this->app->menuService->find($menu1->getId());

        /** @var \Entity\Menu $menu2 */
        $menu2 = $this->app->menuService->find($menu2->getId());

        $this->assertTrue($menu2->getIsMain(),'menu2 should be main');
        $this->assertTrue($menu1->getIsMain(),'menu1 shouldnt be main');
    }


}