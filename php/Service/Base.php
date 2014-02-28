<?php

namespace Service;

use Doctrine\ODM\MongoDB\DocumentManager;

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
    function findBy(array $criteria = array(), $sort = null, $limit = null, $skip = null)
    {
        return $this->dm->getRepository($this->getClassName())->findBy($criteria, $sort, $limit, $skip);
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

    function delete($model, $flush = true)
    {
        $this->dm->clear($model);
        if ($flush) {
            $this->dm->flush($model);
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