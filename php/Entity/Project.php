<?php

namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Project
 * represent a project
 * has many images
 * has one user
 * @ODM\Document
 */
class Project
{
    /** @ODM\Id */
    private $id;
    /** @ODM\String */
    private $title;
    /** @ODM\String */
    private $description;
    /** @ODM\String */
    private $language;
    /** @ODM\String*/
    private $client;
    /** @ODM\Collection */
    private $tags;
    /**
     * @var ArrayCollection[\Entity\Image]
     * @ODM\EmbedMany(targetDocument="\Entity\Image")
     */
    private $images;
    /**
     * @ODM\ReferenceMany(targetDocument="\Entity\User")
     * @var \Entity\User
     */
    private $owner;

    function __constructor()
    {
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getClient(){
        return $this->client;
    }

    public function setClient($client){
        $this->client=$client;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images)
    {
        $this->images = $images;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
    }


}