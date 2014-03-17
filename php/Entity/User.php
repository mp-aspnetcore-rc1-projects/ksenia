<?php

namespace Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints;

/** @ODM\Document */
class User implements UserInterface
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
    /**
     * @ODM\Collection
     */
    private $roles;
    /**
     * @ODM\String;
     */
    private $password;
    /**
     * @ODM\String;
     */
    private $salt;
    /**
     * @ODM\String;
     */
    private $username;
    /**
     * @ODM\String;
     */
    private $email;

    function __construct() {
        $this->roles = array();
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    /**
     * @param \MongoId $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return \MongoId
     */
    public function getId() {
        return $this->id;
    }

    public function setMenus($menus) {
        $this->menus = $menus;
    }

    public function getMenus() {
        return $this->menus;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setProjects($projects) {
        $this->projects = $projects;
    }

    public function getProjects() {
        return $this->projects;
    }

    public function setRoles($roles) {
        $this->roles = $roles;
    }

    public function addRole($role) {
        $this->roles[] = $role;
    }


    public function getRoles() {
        return $this->roles;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {
        $this->password = "";
        $this->salt = "";
    }

    public function __toString() {
        return $this->username;
    }

    /**
     * validation
     * @param ClassMetadata $classMetaData
     */
    static function loadValidatorMetadata(ClassMetaData $classMetaData) {
        // registration group
        $classMetaData
            ->addPropertyConstraint('username', new Constraints\Length(array('min' => 5, 'max' => 100, 'groups' => array('registration'))))
            ->addPropertyConstraint('password', new Constraints\Length(array('min' => 5, 'max' => 100, 'groups' => array('registration'))))
            ->addPropertyConstraint('email', new Constraints\Email(array('groups' => array('registration'))))
            ->addPropertyConstraint('email', new Constraints\Length(array('min' => 6, 'max' => 200, 'groups' => array('registration'))));
    }
}
