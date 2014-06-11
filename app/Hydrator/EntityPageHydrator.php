<?php

namespace Hydrators;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class EntityPageHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = $value instanceof \MongoId ? (string) $value : $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['title'])) {
            $value = $data['title'];
            $return = (string) $value;
            $this->class->reflFields['title']->setValue($document, $return);
            $hydratedData['title'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['description'])) {
            $value = $data['description'];
            $return = (string) $value;
            $this->class->reflFields['description']->setValue($document, $return);
            $hydratedData['description'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['language'])) {
            $value = $data['language'];
            $return = (string) $value;
            $this->class->reflFields['language']->setValue($document, $return);
            $hydratedData['language'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['category'])) {
            $value = $data['category'];
            $return = (string) $value;
            $this->class->reflFields['category']->setValue($document, $return);
            $hydratedData['category'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['content'])) {
            $value = $data['content'];
            $return = (string) $value;
            $this->class->reflFields['content']->setValue($document, $return);
            $hydratedData['content'] = $return;
        }

        /** @Field(type="date") */
        if (isset($data['created_at'])) {
            $value = $data['created_at'];
            if ($value instanceof \MongoDate) { $return = new \DateTime(); $return->setTimestamp($value->sec); } elseif (is_numeric($value)) { $return = new \DateTime(); $return->setTimestamp($value); } elseif ($value instanceof \DateTime) { $return = $value; } else { $return = new \DateTime($value); }
            $this->class->reflFields['created_at']->setValue($document, clone $return);
            $hydratedData['created_at'] = $return;
        }

        /** @Field(type="date") */
        if (isset($data['updated_at'])) {
            $value = $data['updated_at'];
            if ($value instanceof \MongoDate) { $return = new \DateTime(); $return->setTimestamp($value->sec); } elseif (is_numeric($value)) { $return = new \DateTime(); $return->setTimestamp($value); } elseif ($value instanceof \DateTime) { $return = $value; } else { $return = new \DateTime($value); }
            $this->class->reflFields['updated_at']->setValue($document, clone $return);
            $hydratedData['updated_at'] = $return;
        }

        /** @Field(type="boolean") */
        if (isset($data['isPublished'])) {
            $value = $data['isPublished'];
            $return = (bool) $value;
            $this->class->reflFields['isPublished']->setValue($document, $return);
            $hydratedData['isPublished'] = $return;
        }

        /** @ReferenceOne */
        if (isset($data['owner'])) {
            $reference = $data['owner'];
            if (isset($this->class->fieldMappings['owner']['simple']) && $this->class->fieldMappings['owner']['simple']) {
                $className = $this->class->fieldMappings['owner']['targetDocument'];
                $mongoId = $reference;
            } else {
                $className = $this->unitOfWork->getClassNameForAssociation($this->class->fieldMappings['owner'], $reference);
                $mongoId = $reference['$id'];
            }
            $targetMetadata = $this->dm->getClassMetadata($className);
            $id = $targetMetadata->getPHPIdentifierValue($mongoId);
            $return = $this->dm->getReference($className, $id);
            $this->class->reflFields['owner']->setValue($document, $return);
            $hydratedData['owner'] = $return;
        }
        return $hydratedData;
    }
}