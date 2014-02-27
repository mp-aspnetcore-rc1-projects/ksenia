<?php
namespace Service;

use Doctrine\ODM\MongoDB\DocumentManager;

class Project extends Base
{
    function __construct(DocumentManager $dm)
    {
        parent::__construct($dm,'\Entity\Project');
    }

}