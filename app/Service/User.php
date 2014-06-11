<?php
namespace Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Entity\User as UserEntity;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

/**
 * Class Project
 * @package Service
 * @type Base<\Entity\Project>
 */
class User extends Base
{
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private $encoderFactory;

    function __construct(DocumentManager $dm, EncoderFactory $encoderFactory) {
        $this->encoderFactory = $encoderFactory;
        parent::__construct($dm, '\Entity\User');
    }

    function makeSalt() {
        return uniqid();
    }

    function getEncoderFactory() {
        return $this->encoderFactory;
    }

    function register(UserEntity $user) {
        $user->addRole('ROLE_USER');
        $user->setSalt($this->makeSalt());
        $user->setPassword($this->getEncoderFactory()->getEncoder($user)->encodePassword($user->getPassword(), $user->getSalt()));
        $this->create($user);
    }
}