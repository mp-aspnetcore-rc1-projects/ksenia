<?php
namespace Service;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class Project
 * @package Service
 * @type Base<\Entity\Project>
 */
class Configuration extends Base
{

    function __construct(DocumentManager $dm)
    {
        parent::__construct($dm,'\Entity\Configuration');
    }

    function find(){
    	$configuration=$this->findOneBy(array());
    	if($configuration==null){
    		$configuration=new \Entity\Configuration;
    	}
    	return $configuration;
    }

}