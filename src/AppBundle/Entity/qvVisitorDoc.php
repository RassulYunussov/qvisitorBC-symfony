<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvVisitorDoc
 *
 * @ORM\Table(name="qvvisitordoc", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_VisitorDoc_UNIQUE", columns={"id"})}, 
 * indexes={@ORM\Index(name="fk_visitor_visitordoc_idx", columns={"visitorid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvVisitorDocRepository")
 */
class qvVisitorDoc
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
     * @ORM\Column(name="number", type="string", length=45, nullable=false)
     */
    private $number;

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
     * @var \DateTime
     *
     * @ORM\Column(name="issuedate", type="datetime", nullable=true)
     */
    private $issuedate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiredate", type="datetime", nullable=true)
     */
    private $expiredate;

    /**
     * @var \AppBundle\Entity\qvVisitor
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvVisitor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visitorid", referencedColumnName="id")
     * })
     */
    private $visitorid;



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
     * Set number
     *
     * @param string $number
     *
     * @return qvVisitorDoc
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return qvVisitorDoc
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
     * @return qvVisitorDoc
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
     * Set issuedate
     *
     * @param \DateTime $issuedate
     *
     * @return qvVisitorDoc
     */
    public function setIssuedate($issuedate)
    {
        $this->issuedate = $issuedate;

        return $this;
    }

    /**
     * Get issuedate
     *
     * @return \DateTime
     */
    public function getIssuedate()
    {
        return $this->issuedate;
    }

    /**
     * Set expiredate
     *
     * @param \DateTime $expiredate
     *
     * @return qvVisitorDoc
     */
    public function setExpiredate($expiredate)
    {
        $this->expiredate = $expiredate;

        return $this;
    }

    /**
     * Get expiredate
     *
     * @return \DateTime
     */
    public function getExpiredate()
    {
        return $this->expiredate;
    }

    /**
     * Set visitorid
     *
     * @param \AppBundle\Entity\qvVisitor $visitorid
     *
     * @return qvVisitorDoc
     */
    public function setVisitorid(\AppBundle\Entity\qvVisitor $visitorid = null)
    {
        $this->visitorid = $visitorid;

        return $this;
    }

    /**
     * Get visitorid
     *
     * @return \AppBundle\Entity\qvVisitor
     */
    public function getVisitorid()
    {
        return $this->visitorid;
    }
}
