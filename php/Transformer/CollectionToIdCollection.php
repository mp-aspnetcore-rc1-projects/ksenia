<?php

namespace Transformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CollectionToIdCollection implements DataTransformerInterface
{

    /**
     * @inheritdoc
     */
    public function transform($value)
    {
        if ($value && is_array($value)) {
            return json_encode(array_map(function ($el) {
                return $el->getId();
            }, $value));
        }
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value)
    {
        $result = null;
        if ($value) {
            try {
                $result = json_decode($value);
            } catch (\Exception $e) {
                $result = null;
            }
        }
        return $result;
    }
}