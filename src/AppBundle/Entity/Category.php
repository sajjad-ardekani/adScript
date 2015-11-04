<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CategoryRepository")
 */
class Category {

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
     * @ORM\OneToMany(targetEntity="Ad", mappedBy="categories")
     * */
    private $ads;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * */
    private $sub_category;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="sub_category")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * */
    private $parent;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Type", mappedBy="category")
     */
    private $types;

    public function __toString() {
        if ($this->parent) {
            $s = "--";
        } else {
            $s = "";
        }
        return  $s . $this->name;
    }

    public function __construct() {
        $this->ads = new ArrayCollection();
        $this->sub_category = new ArrayCollection();
        $this->types = new ArrayCollection();
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
     * @return Category
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
     * Add ad
     *
     * @param \AppBundle\Entity\Ad $ad
     *
     * @return Category
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

    /**
     * Add subCategory
     *
     * @param \AppBundle\Entity\Category $subCategory
     *
     * @return Category
     */
    public function addSubCategory(\AppBundle\Entity\Category $subCategory) {
        $this->sub_category[] = $subCategory;

        return $this;
    }

    /**
     * Remove subCategory
     *
     * @param \AppBundle\Entity\Category $subCategory
     */
    public function removeSubCategory(\AppBundle\Entity\Category $subCategory) {
        $this->sub_category->removeElement($subCategory);
    }

    /**
     * Get subCategory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubCategory() {
        return $this->sub_category;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Category $parent
     *
     * @return Category
     */
    public function setParent(\AppBundle\Entity\Category $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Category
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Add type
     *
     * @param \AppBundle\Entity\Type $type
     *
     * @return Category
     */
    public function addType(\AppBundle\Entity\Type $type)
    {
        $this->types[] = $type;

        return $this;
    }

    /**
     * Remove type
     *
     * @param \AppBundle\Entity\Type $type
     */
    public function removeType(\AppBundle\Entity\Type $type)
    {
        $this->types->removeElement($type);
    }

    /**
     * Get types
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypes()
    {
        return $this->types;
    }
}
