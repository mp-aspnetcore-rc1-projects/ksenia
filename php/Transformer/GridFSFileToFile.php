<?php

namespace Transformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\MongoDB\GridFSFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GridFSFileToFile implements DataTransformerInterface
{

    /**
     * @inheritdoc
     */
    public function transform($value)
    {
        /** @var GridFSFile $value */
        if ($value) {
            $file = new File($value->getFilename(), false);
            return $file;
        }
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value)
    {
        /** @var UploadedFile $value */
        if ($value) {
            $fs = new GridFSFile();
            $f = $value->move(getenv('TEMP'), uniqid($value->getBasename().'_') . "." . $value->guessExtension());
            $fs->setFilename($f->getPathname());
            return $fs;
        }
    }
}