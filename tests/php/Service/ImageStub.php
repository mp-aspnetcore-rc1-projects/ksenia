<?php

namespace Service;

use Entity\Image as ImageEntity;
use Symfony\Component\HttpFoundation\File\File;

class ImageStub
{


    /**
     * @var Base
     */
    private $projectService;

    public $images = array();

    /**
     * @return \Service\Base
     */
//    public function getProjectService() {
//        return $this->projectService;
//    }
//
    function create($model)
    {
        /** @var ImageEntity $model */
        //if (!$model->getProject()) throw new \Exception("image should have a project!");
        $this->images[] = $model;
    }


//    function __construct(DocumentManager $dm, Base $projectService) {
//        parent::__construct($dm, '\Entity\Image');
//        $this->projectService = $projectService;
//    }

    /**
     * @param array $files
     * @param bool $flush
     */
//    function fromFile(File $file, $flush = true) {
//        $image = new ImageEntity();
//        $image->setTitle($file->getBasename());
//        $image->setDescription($file->getBasename());
//        $image->setExtension($file->getExtension());
//        $image->setBasename($file->getFileInfo()->getBasename());
//        $file = new GridFSFile($file->getRealPath());
//        $image->setFile($file);
//        $image->setIsPublished(true);
//        $this->getDm()->persist($image);
//        if ($flush) $this->getDm()->flush($image);
//        return $image;
//
//    }

    /** mark an image as poster */
//    function markAsPoster(ImageEntity $image, $flush = true) {
//
//        $project = $image->getProject();
//        $project->setPoster($image);
//        $this->getDm()->persist($project);
//        if ($flush == true) $this->getDm()->flush();
//
//    }
//
//    /** remove an image */
//    function remove($image, $flush = true) {
//        /** @var ImageEntity $image */
//        if ($image->getProject()) {
//            $image->getProject()->removeImage($image);
//        }
//        parent::remove($image, $flush); //
//    }

    /** find all published images */
    function findAllPublishedImages()
    {
        return array_filter($this->images, function (ImageEntity $image) {
            return $image->getIsPublished();
        });
    }

}