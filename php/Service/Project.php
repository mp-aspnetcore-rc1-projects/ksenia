<?php
namespace Service;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class Project
 * @package Service
 * @type Base<\Entity\Project>
 */
class Project extends Base
{

    function findAllPublishedProjects() {
        return $this->getRepository()->findBy(array('isPublished'=>true));
    }

    function __construct(DocumentManager $dm) {
        parent::__construct($dm, '\Entity\Project');
    }

}