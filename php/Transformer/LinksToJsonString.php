<?php

namespace Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class LinksToJsonString implements DataTransformerInterface
{

    /**
     * @inheritdoc
     */
    public function transform($value)
    {
        if ($value) {
            return json_encode($value);
        }
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value)
    {
        if ($value) {
            return json_decode($value);
        }
    }
}