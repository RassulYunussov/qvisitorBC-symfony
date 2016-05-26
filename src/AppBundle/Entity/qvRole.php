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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\qvUser", mappedBy="roleid")
     */
    private $userid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userid = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add userid
     *
     * @param \AppBundle\Entity\qvUser $userid
     *
     * @return qvRole
     */
    public function addUserid(\AppBundle\Entity\qvUser $userid)
    {
        $this->userid[] = $userid;

        return $this;
    }

    /**
     * Remove userid
     *
     * @param \AppBundle\Entity\qvUser $userid
     */
    public function removeUserid(\AppBundle\Entity\qvUser $userid)
    {
        $this->userid->removeElement($userid);
    }

    /**
     * Get userid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserid()
    {
        return $this->userid;
    }
}
