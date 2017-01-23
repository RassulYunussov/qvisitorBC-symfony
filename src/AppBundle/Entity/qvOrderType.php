<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvOrderType
 *
 * @ORM\Table(name="qvordertype", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_OrderType_UNIQUE", columns={"id"}), @ORM\UniqueConstraint(name="name_OrderType_UNIQUE", columns={"name"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvOrderTypeRepository")
 */
class qvOrderType
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


 /**
     * Get str
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
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
     * @return qvOrderType
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
