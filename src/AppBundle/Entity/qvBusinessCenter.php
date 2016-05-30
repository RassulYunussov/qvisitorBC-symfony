<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvBusinessCenter
 *
 * @ORM\Table(name="qvbusinesscenter", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_BusinessCenter_UNIQUE", columns={"id"}), @ORM\UniqueConstraint(name="name_BusinessCenter_UNIQUE", columns={"name"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvBusinessCenterRepository")
 */
class qvBusinessCenter
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
     * @var string
     *
     * @ORM\Column(name="owner", type="string", length=200, nullable=true)
     */
    private $owner;


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
     * @return qvBusinessCenter
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
    
/**
     * toString name
     * 
     * return string
     */
    public function __toString() 
	{
    	return  $this->name;
    }
    
    /**
     * Set owner
     *
     * @param string $owner
     *
     * @return qvBusinessCenter
     */
    public function setOwner($owner)
    {
    	$this->owner = $owner;
    
    	return $this;
    }
    
    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
    	return $this->owner;
    }
    
    
}
