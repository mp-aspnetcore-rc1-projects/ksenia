<?php
namespace Service;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class Project
 * @package Service
 * @type Base<\Entity\Project>
 */
class Menu extends Base
{
    function __construct(DocumentManager $dm)
    {
        parent::__construct($dm,'\Entity\Menu');
    }

}