<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\DocumentRepository")
 * * @ORM\HasLifecycleCallbacks
 */
class Image {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, name="filename")
     * @var string $filename
     */
    protected $filename;
    /**
     * @ORM\ManyToOne(targetEntity="Ad", inversedBy="images")
     * @ORM\JoinColumn(name="ad_id", referencedColumnName="id")
     * */
    private $ad;


//    /**
//     * Image file
//     *
//     * @var File
//     *
//     * @Assert\File(
//     *     maxSize = "5M",
//     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
//     *     maxSizeMessage = "The maxmimum allowed file size is 5MB.",
//     *     mimeTypesMessage = "Only the filetypes image are allowed."
//     * )
//     */
//    private $file;
//
//    /**
//     * Sets file.
//     *
//     * @param UploadedFile $file
//     */
//    public function setFile(UploadedFile $file = null) {
//        $this->file = $file;
//        // check if we have an old image path
//        if (isset($this->path)) {
//            // store the old name to delete after the update
//            $this->temp = $this->path;
//            $this->path = null;
//        } else {
//            $this->path = 'initial';
//        }
//    }
//
//    /**
//     * Get file.
//     *
//     * @return UploadedFile
//     */
//    public function getFile() {
//        return $this->file;
//    }
//
//    public function getAbsolutePath() {
//        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->id . '.' . $this->path;
//    }
//
//    public function getWebPath() {
//        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
//    }
//
//    protected function getUploadRootDir() {
//        // the absolute directory path where uploaded
//        // documents should be saved
//        return __DIR__ . '/../../../web/' . $this->getUploadDir();
//    }
//
//    protected function getUploadDir() {
//        // get rid of the __DIR__ so it doesn't screw up
//        // when displaying uploaded doc/image in the view.
//        return 'uploads/documents';
//    }
//
//    /**
//     * @ORM\PostPersist()
//     * @ORM\PostUpdate()
//     */
//    public function upload() {
//        if (null === $this->getFile()) {
//            return;
//        }
//
//        // if there is an error when moving the file, an exception will
//        // be automatically thrown by move(). This will properly prevent
//        // the entity from being persisted to the database on error
//        $this->getFile()->move($this->getUploadRootDir(), $this->path);
//
//        // check if we have an old image
//        if (isset($this->temp)) {
//            // delete the old image
//            unlink($this->getUploadRootDir() . '/' . $this->temp);
//            // clear the temp image path
//            $this->temp = null;
//        }
//        $this->file = null;
//    }
//
//    /**
//     * @ORM\PrePersist()
//     * @ORM\PreUpdate()
//     */
//    public function preUpload() {
//        if (null !== $this->getFile()) {
//            // do whatever you want to generate a unique name
//            $filename = sha1(uniqid(mt_rand(), true));
//            $this->path = $filename . '.' . $this->getFile()->guessExtension();
//        }
//    }
//
//    /**
//     * @ORM\PostRemove()
//     */
//    public function removeUpload() {
//        $file = $this->getAbsolutePath();
//        if ($file) {
//            unlink($file);
//        }
//    }
//
    public function __toString() {
        return $this->getFilename();
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set ad
     *
     * @param \AppBundle\Entity\Ad $ad
     *
     * @return Image
     */
    public function setAd(\AppBundle\Entity\Ad $ad = null) {
        $this->ad = $ad;

        return $this;
    }

    /**
     * Get ad
     *
     * @return \AppBundle\Entity\Ad
     */
    public function getAd() {
        return $this->ad;
    }


    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Image
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }
}
