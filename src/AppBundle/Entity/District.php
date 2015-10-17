<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * District
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class District {

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
     * @ORM\ManyToOne(targetEntity="City", inversedBy="districts")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="Ad", mappedBy="district")
     * */
    private $ads;
    public function __toString() {
        return $this->name;
    }
    public function __construct() {
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
     * @return District
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
     * Set city
     *
     * @param \AppBundle\Entity\City $city
     *
     * @return District
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
     * Add ad
     *
     * @param \AppBundle\Entity\Ad $ad
     *
     * @return District
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
