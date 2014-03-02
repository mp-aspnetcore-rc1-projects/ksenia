<?php

namespace Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockMode;

/**
 * Class Project
 * @package Service
 * @template<T>
 */
class Base
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    /**
     * @var <T> $class
     */
    private $class;

    function __construct(DocumentManager $dm, $class)
    {
        $this->dm = $dm;
        $this->class = $class;
    }

    /**
     * @param $id
     * @return <T> $class
     */
    function find($id)
    {
        return $this->dm->getRepository($this->getClassName())->find($id);
    }

    /**
     * @return array<T>
     */
    function findAll()
    {
        return $this->dm->getRepository($this->getClassName())->findAll();
    }

    /**
     * @param array $criteria
     * @param null $sort
     * @param null $limit
     * @param null $skip
     * @return array<T>
     */
    function findBy(array $criteria = array(),array $sort = null, $limit = null, $skip = null)
    {
        return $this->dm->getRepository($this->getClassName())->findBy($criteria, $sort, $limit, $skip);
    }

    function findOneBy(array $criteria,array $order=array()){
        return $this->getDm()->getRepository($this->getClassName())->findOneBy($criteria);
    }
    /**
     * @param array $criteria
     * @param null $sort
     * @param null $limit
     * @param null $skip
     * @return int
     */
    function count(array $criteria = array(), $sort = null, $limit = null, $skip = null)
    {
        return count($this->findBy($criteria, $sort, $limit, $skip));
    }

    function create($model, $flush = true)
    {
        $this->dm->persist($model);
        if ($flush) {
            $this->dm->flush();
        }
    }

    function update($model, array $where=null, $flush = true)
    {
        if ($where==null) {
            $where=array('id' => $model->getId());
        }
        $lookup = $this->findOneBy($where);
        if ($lookup) {
            $this->dm->persist($model);
        }
        if ($flush) {
            $this->dm->flush();
        }
    }

    function remove($model, $flush = true)
    {
        $this->dm->remove($model);
        if ($flush) {
            $this->dm->flush();
        }
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->class;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDm()
    {
        return $this->dm;
    }


}