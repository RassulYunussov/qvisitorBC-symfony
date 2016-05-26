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
     * @var \AppBundle\Entity\Qvgender
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Qvgender")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="genderid", referencedColumnName="id")
     * })
     */
    private $genderid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\qvOrder", inversedBy="visitorid")
     * @ORM\JoinTable(name="rf_visitor_order",
     *   joinColumns={
     *     @ORM\JoinColumn(name="visitorid", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="orderid", referencedColumnName="id")
     *   }
     * )
     */
    private $orderid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set genderid
     *
     * @param \AppBundle\Entity\Qvgender $genderid
     *
     * @return qvVisitor
     */
    public function setGenderid(\AppBundle\Entity\Qvgender $genderid = null)
    {
        $this->genderid = $genderid;

        return $this;
    }

    /**
     * Get genderid
     *
     * @return \AppBundle\Entity\Qvgender
     */
    public function getGenderid()
    {
        return $this->genderid;
    }

    /**
     * Add orderid
     *
     * @param \AppBundle\Entity\qvOrder $orderid
     *
     * @return qvVisitor
     */
    public function addOrderid(\AppBundle\Entity\qvOrder $orderid)
    {
        $this->orderid[] = $orderid;

        return $this;
    }

    /**
     * Remove orderid
     *
     * @param \AppBundle\Entity\qvOrder $orderid
     */
    public function removeOrderid(\AppBundle\Entity\qvOrder $orderid)
    {
        $this->orderid->removeElement($orderid);
    }

    /**
     * Get orderid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderid()
    {
        return $this->orderid;
    }
}
