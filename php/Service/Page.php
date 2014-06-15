<?php
namespace Service;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class Project
 * @package Service
 * @type Base<\Entity\Project>
 */
class Page extends Base
{
    /** find all public pages */
    function findAllPublishedPages() {
        return $this->getRepository()->findBy(array('isPublished'=>true));
    }
    function __construct(DocumentManager $dm) {
        parent::__construct($dm, '\Entity\Page');
    }

}
