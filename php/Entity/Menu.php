<?php

namespace Entity;

use Doctrine\MongoDB\GridFSFile;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JsonSerializable;
use Doctrine\Common\Collections\ArrayCollection;
use Mparaiso\SimpleRest\Model\IModel;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Image
 * @package Entity
 * @ODM\Document
 */
class Menu
{
    /** @ODM\Id */
    private $id;
    /** @ODM\String */
    private $title;
    /** @ODM\String */
    private $description;
    /** @ODM\Boolean */
    private $isPublished;
    /** @ODM\Date */
    private $createdAt;
    /** @ODM\Date */
    private $updatedAt;
    /** @ODM\ReferenceOne(targetDocument="\Entity\User",inversedBy="menus",simple=true) */
    private $owner;
    /** @ODM\ReferenceMany(targetDocument="\Entity\Link",mappedBy="menu",simple=true,cascade="all") */
    private $links;

    function __construct() {
        $this->links = new ArrayCollection;
    }


    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setIsPublished($isPublished) {
        $this->isPublished = $isPublished;
    }

    public function getIsPublished() {
        return $this->isPublished;
    }

    public function addLinks(\Entity\Link $links) {
        $links->setMenu($this);
        $this->links->add($links);
    }

    public function removeLinks($links) {
        $this->links->remove($links);
    }

    /**
     * @param Link[] $links
     */
    public function setLinks($links) {
        $this->links=$links;
    }

    /**
     * @return Link[]
     */
    public function getLinks() {
        return $this->links->toArray();
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }
}