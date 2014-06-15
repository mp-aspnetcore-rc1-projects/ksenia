<?php
namespace Service;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class Project
 * @package Service
 * @type Base<\Entity\Project>
 */
class Menu extends Base
{
    function __construct(DocumentManager $dm) {
        parent::__construct($dm, '\Entity\Menu');
    }

    function markAsMain(\Entity\Menu $menu) {
        $menu->setIsMain(true);
        $this->getDm()->persist($menu);
        $qb = $this->getDm()->createQueryBuilder('\Entity\Menu');
        $result = $qb->update()
            ->multiple(true)
            ->field('id')->notEqual($menu->getId())
            ->field('isMain')->set(false)
            ->getQuery()
            ->execute();
        $this->getDm()->flush();
        return $result;
    }

    function update($model, array $where = null, $flush = true) {
        $linksToRemove = $this->getDm()->getRepository('\Entity\Link')->findBy(array("menu" => $model->getId()));
        //remove all the links that own a menu id eual to the current menu model id
        foreach ($linksToRemove as $link) {
            $this->getDm()->remove($link);
        }
        parent::update($model, $where, $flush);
    }
    /** create a new menu */
    function create($model, $flush = true) {
        /** @var \Entity\Menu $model */
        foreach ($model->getLinks() as $link) {
            $link->setMenu($model);
        }
        parent::create($model, $flush);
        if ($model->getIsMain()) {
            //mark as main,unmark other menus as main
            $this->markAsMain($model);
        }
    }

    /** find published menu */
    function findAllPublishedMenus(){
        return $this->getRepository()->findBy(array('isPublished'=>true));
    }


}