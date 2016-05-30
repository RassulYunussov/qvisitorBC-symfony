<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvGender
 *
 * @ORM\Table(name="qvgender", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_Gender_UNIQUE", columns={"id"}), @ORM\UniqueConstraint(name="name_Gender_UNIQUE", columns={"name"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvGenderRepository")
 */
class qvGender
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

 public function __toString()
    {
    	return $this->name;
    }
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return qvGender
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
