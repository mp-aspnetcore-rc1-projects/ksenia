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

    function update($model, array $where = null, $flush = true) {
        $linksToRemove = $this->getDm()->getRepository('\Entity\Link')->findBy(array("menu" => $model->getId()));
        //remove all the links that own a menu id eual to the current menu model id
        foreach ($linksToRemove as $link) {
            $this->getDm()->remove($link);
        }
        parent::update($model, $where, $flush);
    }

    function create($model, $flush = true) {
        /** @var \Entity\Menu $model */
        foreach ($model->getLinks() as $link) {
            $link->setMenu($model);
        }
        parent::create($model, $flush);
    }


}