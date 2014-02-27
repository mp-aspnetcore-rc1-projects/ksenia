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
}