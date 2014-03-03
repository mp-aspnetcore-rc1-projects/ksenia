<?php

namespace Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User{
	/**
	 * @ODM\Id
	 * @var \MongoId 
	 */
	private $id;
    /**
     * @ODM\ReferenceMany(name="projects",targetDocument="\Entity\Image",cascade="all",mappedBy="user",simple=true)
     */
    private $projects;
}