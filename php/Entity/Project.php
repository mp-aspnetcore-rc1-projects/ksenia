<?php

namespace Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JsonSerializable;

/**
 * Class Project
 * represent a project
 * has many images
 * has one user
 * @ODM\Document
 * @ODM\HasLifecycleCallbacks
 */
class Project implements JsonSerializable
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
     * @ODM\ReferenceMany(targetDocument="\Entity\Image",cascade="all",inversedBy="project",simple=true)
     */
    private $images;
    /** @ODM\ReferenceOne(targetDocument="\Entity\Image",cascade="all",simple=true) */
    private $poster;
    /**
     * @ODM\ReferenceMany(targetDocument="\Entity\User",cascade="all",inversedBy="project",simple=true)
     * @var \Entity\User
     */
    private $owner;
    /**
     * @ODM\Boolean
     */
    public $isPublished;
    /** @ODM\Date */
    public $createdAt;
    /** @ODM\Date */
    public $updatedAt;

    function __constructor()
    {
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;
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

    /**
     * @param $id
     * @return \Entity\Image
     */
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
        $i = $this->getImageById($image->getId());
        if ($i) {
            $this->images->removeElement($i);
        }
        $i->setProject(null);
        return $i;
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
        $new->images->clear();
        return $new;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPoster()
    {
        return $this->poster;
    }

    public function setPoster($poster)
    {
        $this->poster = $poster;
    }


    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /** @ODM\PrePersist */
    public function prePersist()
    {
        $this->setUpdatedAt(new DateTime());
        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new DateTime);
        }
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return array(
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "description" => $this->getDescription(),
            "language" => $this->getLanguage(),
            "owner" => $this->getOwner(),
            "isPublished" => $this->getIsPublished(),
            "client" => $this->getClient(),
            "poster" => $this->getPoster(),
            "images" => $this->getImages()->toArray(),
            "tags" => $this->getTags(),
            "createdAt" => $this->getCreatedAt(),
            "updatedAt" => $this->getUpdatedAt()
        );
    }
}