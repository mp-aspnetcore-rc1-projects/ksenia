<?php
namespace Service;

use Doctrine\MongoDB\GridFSFile;
use Doctrine\ODM\MongoDB\DocumentManager;
use Entity\Image as ImageEntity;
use Symfony\Component\HttpFoundation\File\File;
use Mparaiso\SimpleRest\Service\IService;

class Image extends Base implements IService
{


    /**
     * @var Base
     */
    private $projectService;


    /**
     * @return \Service\Base
     */
    public function getProjectService() {
        return $this->projectService;
    }

    function create($model, $flush = true) {
        /** @var ImageEntity $model */
        //if (!$model->getProject()) throw new \Exception("image should have a project!");
        parent::create($model, $flush); // TODO: Change the autogenerated stub
    }


    function __construct(DocumentManager $dm, Base $projectService) {
        parent::__construct($dm, '\Entity\Image');
        $this->projectService = $projectService;
    }

    /**
     * @param array $files
     * @param bool  $flush
     */
    function fromFile(File $file, $flush = true) {
        $image = new ImageEntity();
        $image->setTitle($file->getBasename());
        $image->setDescription($file->getBasename());
        $image->setExtension($file->getExtension());
        $image->setBasename($file->getFileInfo()->getBasename());
        $file = new GridFSFile($file->getRealPath());
        $image->setFile($file);
        $image->setIsPublished(true);
        $this->getDm()->persist($image);
        if ($flush) $this->getDm()->flush($image);
        return $image;

    }

    /** mark an image as poster */
    function markAsPoster(ImageEntity $image, $flush = true) {

        $project = $image->getProject();
        $project->setPoster($image);
        $this->getDm()->persist($project);
        if ($flush == true) $this->getDm()->flush();

    }

    /** remove an image */
    function remove($image, $flush = true) {
        /** @var ImageEntity $image */
        if ($image->getProject()) {
            $image->getProject()->removeImage($image);
        }
        parent::remove($image, $flush); // TODO: Change the autogenerated stub
    }

    /** find all published images */
    function findAllPublishedImages() {
        return $this->getRepository()->findBy(array('isPublished'=>true));
    }

}