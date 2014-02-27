<?php

namespace Service;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class Project
 * @package Service
 */
class Base
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    /**
     * @var string
     */
    private $class;

    function __construct(DocumentManager $dm, $class)
    {
        $this->dm = $dm;
        $this->class = $class;
    }

    function find($id)
    {
        return $this->dm->getRepository($this->getClassName())->find($id);
    }

    function findAll()
    {
        return $this->dm->getRepository($this->getClassName())->findAll();
    }

    function findBy(array $criteria = array(), $sort = null, $limit = null, $skip = null)
    {
        return $this->dm->getRepository($this->getClassName())->findBy($criteria, $sort, $limit, $skip);
    }

    function count(array $criteria = array(), $sort = null, $limit = null, $skip = null)
    {
        return count($this->findBy($criteria, $sort, $limit, $skip));
    }

    function insert($model, $flush = true)
    {
        $this->dm->persist($model);
        if ($flush) {
            $this->dm->flush($model);
        }
    }

    function update($model, $id = null, $flush = true)
    {
        if ($id == null) {
            $id = $model->getId();
        }
        $lookup = $this->find($id);
        if ($lookup) {
            $this->dm->persist($model);
        }
        if ($flush) {
            $this->dm->flush($model);
        }
    }

    function remove($model, $flush = true)
    {
        $this->dm->clear($model);
        if ($flush) {
            $this->dm->flush($model);
        }
    }

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