<?php
namespace Service;

use Doctrine\MongoDB\GridFSFile;
use Doctrine\ODM\MongoDB\DocumentManager;
use Mparaiso\SimpleRest\Model\IModel;
use Symfony\Component\HttpFoundation\File\File;
use Mparaiso\SimpleRest\Service\IService;

class Image extends Base implements IService
{
    function __construct(DocumentManager $dm)
    {
        parent::__construct($dm, '\Entity\Image');
    }

    /**
     * @param array $files
     * @param bool $flush
     */
    function fromFile(File $file, $flush = true)
    {
        $image = new \Entity\Image();
        $image->setTitle($file->getBasename());
        $image->setDescription($file->getBasename());
        $image->setExtension($file->getExtension());
        $image->setBasename($file->getFileInfo()->getBasename());
        $file = new GridFSFile($file->getRealPath());
        $image->setFile($file);
        $image->setIsPublished(true);
        $this->getDm()->persist($image);
        if ($flush) {
            $this->getDm()->flush($image);
        }
        return $image;

    }

}