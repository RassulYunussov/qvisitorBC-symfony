<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvVisitor
 *
 * @ORM\Table(name="qvvisitor", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_Visitor_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_gender_visitor_idx", columns={"genderid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvVisitorRepository")
 */
class qvVisitor
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
     * @ORM\Column(name="firstname", type="string", length=45, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=45, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="patronimic", type="string", length=45, nullable=true)
     */
    private $patronimic;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime", nullable=false)
     */
    private $birthdate;

    /**
     * @var \AppBundle\Entity\qvGender
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvGender")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="genderid", referencedColumnName="id")
     * })
     */
    private $gender;




    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\qvOrder", mappedBy="visitors")
     */

    private $orders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
    }

public function __toString()
{
	return $this->firstname;
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return qvVisitor
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return qvVisitor
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set patronimic
     *
     * @param string $patronimic
     *
     * @return qvVisitor
     */
    public function setPatronimic($patronimic)
    {
        $this->patronimic = $patronimic;

        return $this;
    }

    /**
     * Get patronimic
     *
     * @return string
     */
    public function getPatronimic()
    {
        return $this->patronimic;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return qvVisitor
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set gender
     *
     * @param \AppBundle\Entity\Qvgender $gender
     *
     * @return qvVisitor
     */
    public function setGender(\AppBundle\Entity\qvgender $gender = null)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return \AppBundle\Entity\qvgender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Add orders
     *
     * @param \AppBundle\Entity\qvOrder $orders
     *
     * @return qvVisitor
     */
    public function addOrders(\AppBundle\Entity\qvOrder $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \AppBundle\Entity\qvOrder $orders
     */
    public function removeOrders(\AppBundle\Entity\qvOrder $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
