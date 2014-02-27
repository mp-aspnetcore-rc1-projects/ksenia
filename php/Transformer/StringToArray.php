<?php

namespace Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StringToArray implements DataTransformerInterface
{

    /**
     * @inheritdoc
     */
    public function transform($value)
    {
        if ($value) {
            return implode(',', $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value)
    {
        if ($value) {
            return explode(",", $value);
        }
    }
}