<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EnumValue
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class EnumValue {

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
     * @ORM\ManyToOne(targetEntity="Type",cascade={"remove"})
     * 
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Ad")
     * @ORM\JoinColumn(name="ad_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $ad;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     * 
     */
    private $value;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return IntegerValue
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set type
     *
     * @param \AppBundle\Entity\Type $type
     *
     * @return IntegerValue
     */
    public function setType(\AppBundle\Entity\Type $type = null) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\Type
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set ad
     *
     * @param \AppBundle\Entity\Ad $ad
     *
     * @return IntegerValue
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

}
