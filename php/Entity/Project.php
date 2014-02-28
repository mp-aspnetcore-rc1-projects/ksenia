<?php

namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
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
    /** @ODM\String */
    private $client;
    /** @ODM\Collection */
    private $tags;
    /**
     * @var ArrayCollection[\Entity\Image]
     * @ODM\ReferenceMany(targetDocument="\Entity\Image")
     */
    private $images;
    /**
     * @ODM\ReferenceMany(targetDocument="\Entity\User",cascade="all")
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

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;
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

    public function addImage(Image $image)
    {
        $added = $this->images->add($image);
        $image->setProject($this);
        if (null === $image->getId()) {
            $image->setId(new \MongoId());
        }
        return $added;
    }

    public function getImageById($id)
    {
        /** @var Criteria $matching */
        $matching = Criteria::create()->where(Criteria::expr()->eq('id', $id));
        return $this->getImages()->filter(function ($image) use ($id) {
            return $image->getId() === "$id";
        })->first();
    }

    public function removeImage(Image $image)
    {
        $removed = $this->images->removeElement($image);
        $image->setProject(null);
        return $removed;
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

    function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return Project
     */
    function copy()
    {
        $new = clone($this);
        $new->setId(null);
        $new->images = $this->images->map(function ($image) use ($new) {
            /** @var \Entity\Image $image */
            $image->setId(null);
            $image->setProject($new);
            $gridFsFile = clone $image->getFile();
            $image->setFile($gridFsFile);
            return $image;
        });
        return $new;
    }

    public function setId($id)
    {
        $this->id = $id;
    }


}