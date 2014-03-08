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
 * @ODM\HasLifecycleCallbacks
 */
class Menu
{
    /** @ODM\Id */
    private $id;
    /** @ODM\String @var string */
    private $title;
    /** @ODM\String @var string */
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
    /** @ODM\String  @var string */
    private $language;
    /** @ODM\Boolean  @var boolean */
    private $isMain;

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
        $this->links = $links;
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

    public function setLanguage($language) {
        $this->language = $language;
    }

    public function getLanguage() {
        return $this->language;
    }


    /** @ODM\PrePersist */
    public function prePersist() {
        $this->setUpdatedAt(new \DateTime);
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime);
        }
    }

    public function getIsMain(){
        return $this->isMain;
    }
    
    public function setIsMain($isMain){
         $this->isMain=$isMain;
         return $this;
    }
}