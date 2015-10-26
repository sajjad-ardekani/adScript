<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Image;
use Doctrine\ORM\EntityManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;

class UploadListener {

    protected $manager;

    public function __construct(EntityManager $manager) {
        $this->manager = $manager;
    }

    public function onUpload(PostPersistEvent $event) {
        $request = $event->getRequest();
        $id = $request->get('id');
        $object = new Image();
        $ad = $this->manager->getRepository("AppBundle:Ad")->find($id);
        $object->setAd($ad);
        $file = $event->getFile();

        $object->setFilename($file->getFilename());

        $this->manager->persist($object);
        $this->manager->flush();
    }

}
