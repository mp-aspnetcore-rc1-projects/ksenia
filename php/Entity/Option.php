<?php

namespace Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JsonSerializable;

/**
 * Class Image
 * @package Entity
 * @ODM\Document
 * @ODM\HasLifecycleCallbacks
 */
class Option
{
    /** @ODM\Id */
    private $id;
    /** @ODM\String */
    private $name;
    /** @ODM\String */
    private $description;
    /** @ODM\Boolean */
    private $value;
    /** @ODM\Date */
    private $createdAt;
    /** @ODM\Date */
    private $updatedAt;
    /** @ODM\ReferenceOne(targetDocument="\Entity\User") */
    private $owner;
}