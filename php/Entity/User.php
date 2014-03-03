<?php

namespace Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User
{
    /**
     * @ODM\Id
     * @var \MongoId
     */
    private $id;
    /**
     * @ODM\ReferenceMany(name="projects",targetDocument="\Entity\Image",cascade="delete",mappedBy="user",simple=true)
     */
    private $projects;
    /**
     * @ODM\ReferenceMany(name="menus",targetDocument="\Entity\Menu",mappedBy="owner",cascade="{delete}",simple=true)
     */
    private $menus;
}