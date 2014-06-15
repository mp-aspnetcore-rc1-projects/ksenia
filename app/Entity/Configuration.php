<?php

namespace Entity;

use Doctrine\MongoDB\GridFSMeta;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JsonSerializable;
use Mparaiso\SimpleRest\Model\IModel;
use Symfony\Component\HttpFoundation\Meta\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\Meta\UploadedMeta;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class Configuration
 * @ODM\Document
 * @ODM\HasLifecycleCallbacks
 */
class Configuration implements JsonSerializable, NormalizableInterface
{
    /** @ODM\Id */
    private $id;
    /** @ODM\String */
    private $title;
    /** @ODM\String */
    private $subtitle;
    /** @ODM\String */
    private $description;
    /** @ODM\String */
    private $language;
    /** @ODM\Date */
    private $createdAt;
    /** @ODM\Date */
    private $updatedAt;
    /** @ODM\ReferenceOne(targetDocument="\Entity\User") */
    private $owner;
    /** @ODM\String */
    private $meta;
    /** @ODM\String */
    private $twitterUsername;
    /** @ODM\String */
    private $facebookAppId;
    /**
     * @ODM\String
     * @var string
     */
    private $contactEmail;
    /**
     * @ODM\String
     * @var string
     */
    private $googleSiteVerification;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
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

    /**
     * @return GridFSMeta
     */
    public function getMeta()
    {
        return $this->meta;
    }

    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = array())
    {
        return $this->jsonSerialize();
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
        return get_object_vars(this);
    }

    /**
     * @ODM\PrePersist
     */
    function prePersist()
    {
        if (null == $this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime());
        }
        $this->setUpdatedAt(new \DateTime());
    }

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getTwitterUsername()
    {
        return $this->twitterUsername;
    }

    /**
     * @param string $twitterUsername
     */
    public function setTwitterUsername($twitterUsername)
    {
        $this->twitterUsername = $twitterUsername;
    }

    /**
     * @return string
     */
    public function getFacebookAppId()
    {
        return $this->facebookAppId;
    }

    /**
     * @param string $facebookAppId
     */
    public function setFacebookAppId($facebookAppId)
    {
        $this->facebookAppId = $facebookAppId;
    }

    /**
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param string $contactEmail
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * @return mixed
     */
    public function getGoogleSiteVerification()
    {
        return $this->googleSiteVerification;
    }

    /**
     * @param mixed $googleSiteVerification
     */
    public function setGoogleSiteVerification($googleSiteVerification)
    {
        $this->googleSiteVerification = $googleSiteVerification;
    }
}
