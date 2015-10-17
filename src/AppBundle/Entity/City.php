<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * City
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class City {

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="District", mappedBy="city")
     * */
    private $districts;

    /**
     * @ORM\OneToMany(targetEntity="Ad", mappedBy="city")
     * */
    private $ads;

    public function __toString() {
        return $this->name;
    }

    public function __construct() {
        $this->districts = new ArrayCollection();
        $this->ads = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return City
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add district
     *
     * @param \AppBundle\Entity\District $district
     *
     * @return City
     */
    public function addDistrict(\AppBundle\Entity\District $district) {
        $this->districts[] = $district;

        return $this;
    }

    /**
     * Remove district
     *
     * @param \AppBundle\Entity\District $district
     */
    public function removeDistrict(\AppBundle\Entity\District $district) {
        $this->districts->removeElement($district);
    }

    /**
     * Get districts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDistricts() {
        return $this->districts;
    }

    /**
     * Add ad
     *
     * @param \AppBundle\Entity\Ad $ad
     *
     * @return City
     */
    public function addAd(\AppBundle\Entity\Ad $ad) {
        $this->ads[] = $ad;

        return $this;
    }

    /**
     * Remove ad
     *
     * @param \AppBundle\Entity\Ad $ad
     */
    public function removeAd(\AppBundle\Entity\Ad $ad) {
        $this->ads->removeElement($ad);
    }

    /**
     * Get ads
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAds() {
        return $this->ads;
    }

}
