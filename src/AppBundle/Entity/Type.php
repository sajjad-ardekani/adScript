<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Type
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Type {
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
     * @ORM\ManyToOne(targetEntity="category", inversedBy="types")
     */
    private $category;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * 
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * 
     */
    private $type;
    /**
     * @var string
     *
     * @ORM\Column(name="options", type="array", length=255,nullable=true)
     * 
     */
    private $options;
    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Set type
     *
     * @param string $type
     *
     * @return Type
     */
    public function setType($type) {
        if (!in_array($type, ['integer', 'enom', 'string'])) {
            throw new \Exception("Invalid Type");
        }
        $this->type = $type;
        return $this;
    }
    /**
     * Get type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    /**
     * Set category
     *
     * @param \AppBundle\Entity\category $category
     *
     * @return Type
     */
    public function setCategory(\AppBundle\Entity\category $category = null) {
        $this->category = $category;
        return $this;
    }
    /**
     * Get category
     *
     * @return \AppBundle\Entity\category
     */
    public function getCategory() {
        return $this->category;
    }
    /**
     * Set options
     *
     * @param array $options
     *
     * @return Type
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Type
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
