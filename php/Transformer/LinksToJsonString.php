<?php

namespace Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class LinksToJsonString implements DataTransformerInterface
{

    /**
     * @inheritdoc
     */
    public function transform($value) {
        if ($value) {
            $links = json_encode($value);
            return $links;
        }
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value) {
        if ($value) {
            //file_put_contents('php://stdout',$value);
            $links = array_map(function ($item) {
                $link = new \Entity\Link;
                isset($item['id']) and $link->setId(new \MongoId($item['id']));
                isset($item['itemId']) and $link->setItemId(new \MongoId($item['itemId']));
                isset($item['type']) and  $link->setType($item['type']);
                isset($item['title']) and $link->setTitle($item['title']);
                isset($item['description']) and $link->setDescription($item['description']);
                isset($item['createdAt']) and $link->setCreatedAt($item['createdAt']);
                return $link;
            }, json_decode($value, true));

            return new ArrayCollection($links);
        }
    }
}