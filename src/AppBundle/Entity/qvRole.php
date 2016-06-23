<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvRole
 *
 * @ORM\Table(name="qvrole", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_Role_UNIQUE", columns={"id"}), @ORM\UniqueConstraint(name="name_UNIQUE", columns={"name"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvRoleRepository")
 */
class qvRole
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
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;
    
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
     * @return qvRole
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
    public function __toString()
    {
    	return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return qvRole
     */
    public function setCode($code)
    {
    	$this->code = $code;
    
    	return $this;
    }
    
    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
    	return $this->code;
    }
}
