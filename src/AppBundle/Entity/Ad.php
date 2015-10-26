<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ad
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AdRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Ad {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="phonenumber", type="integer")
     */
    private $phonenumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status = 1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="report", type="boolean")
     */
    private $report = 0;

    /**
     * @ORM\ManyToOne(targetEntity="user", inversedBy="ads")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="ads")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="ads")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="ad")
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="District", inversedBy="ads")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     * */
    private $district;
    private $numberOfWheels;

    /**
     * Get numberOfWheels
     *
     * @return integer
     */
    public function getNumberOfWheels() {
        return $this->numberOfWheels;
    }

    public function __construct() {
        $this->images = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Ad
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Ad
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ad
     */
    public function setPrice($price) {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Ad
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set phonenumber
     *
     * @param integer $phonenumber
     *
     * @return Ad
     */
    public function setPhonenumber($phonenumber) {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    /**
     * Get phonenumber
     *
     * @return integer
     */
    public function getPhonenumber() {
        return $this->phonenumber;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Ad
     */
    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate() {
        return $this->creationDate;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Ad
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set report
     *
     * @param boolean $report
     *
     * @return Ad
     */
    public function setReport($report) {
        $this->report = $report;

        return $this;
    }

    /**
     * Get report
     *
     * @return boolean
     */
    public function getReport() {
        return $this->report;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\user $user
     *
     * @return Ad
     */
    public function setUser(\AppBundle\Entity\user $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\user
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set categories
     *
     * @param \AppBundle\Entity\Category $categories
     *
     * @return Ad
     */
    public function setCategories(\AppBundle\Entity\Category $categories = null) {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Add image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Ad
     */
    public function addImage(\AppBundle\Entity\Image $image) {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \AppBundle\Entity\Image $image
     */
    public function removeImage(\AppBundle\Entity\Image $image) {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue() {
        $this->creationDate = new \DateTime();
    }

    /**
     * Set city
     *
     * @param \AppBundle\Entity\City $city
     *
     * @return Ad
     */
    public function setCity(\AppBundle\Entity\City $city = null) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\City
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set district
     *
     * @param \AppBundle\Entity\District $district
     *
     * @return Ad
     */
    public function setDistrict(\AppBundle\Entity\District $district = null) {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return \AppBundle\Entity\District
     */
    public function getDistrict() {
        return $this->district;
    }
}
