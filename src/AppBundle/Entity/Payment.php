<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Payment {

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
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="discountCode", type="string", length=255)
     */
    private $discountCode;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Ad", mappedBy="payment")
     */
    private $ad;
    

    public function __construct() {
        $this->ad = new ArrayCollection();
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
     * @return Payment
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
     * Set amount
     *
     * @param integer $amount
     *
     * @return Payment
     */
    public function setAmount($amount) {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Payment
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set discountCode
     *
     * @param string $discountCode
     *
     * @return Payment
     */
    public function setDiscountCode($discountCode) {
        $this->discountCode = $discountCode;

        return $this;
    }

    /**
     * Get discountCode
     *
     * @return string
     */
    public function getDiscountCode() {
        return $this->discountCode;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Payment
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
     * Set ad
     *
     * @param Ad $ad
     *
     * @return Payment
     */
    public function setAd(Ad $ad = null) {
        $this->ad = $ad;

        return $this;
    }

    /**
     * Get ad
     *
     * @return Ad
     */
    public function getAd() {
        return $this->ad;
    }

    public function __toString() {
        return $this->name;
    }


    /**
     * Add ad
     *
     * @param \AppBundle\Entity\Ad $ad
     *
     * @return Payment
     */
    public function addAd(\AppBundle\Entity\Ad $ad)
    {
        $this->ad[] = $ad;

        return $this;
    }

    /**
     * Remove ad
     *
     * @param \AppBundle\Entity\Ad $ad
     */
    public function removeAd(\AppBundle\Entity\Ad $ad)
    {
        $this->ad->removeElement($ad);
    }
}
