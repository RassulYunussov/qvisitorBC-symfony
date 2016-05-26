<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvUserPassport
 *
 * @ORM\Table(name="qvuserpassport", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_UserPassport_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_user_userpassport_idx", columns={"userid"}), @ORM\Index(name="fk_gender_userpassport_idx", columns={"genderid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvUserPassportRepository")
 */
class qvUserPassport
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
     * @ORM\Column(name="patronimic", type="string", length=45, nullable=false)
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
    private $genderid;

    /**
     * @var \AppBundle\Entity\qvUser
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userid", referencedColumnName="id")
     * })
     */
    private $userid;



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
     * @return qvUserPassport
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
     * @return qvUserPassport
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
     * @return qvUserPassport
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
     * @return qvUserPassport
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
     * @param \AppBundle\Entity\qvGender $genderid
     *
     * @return qvUserPassport
     */
    public function setGenderid(\AppBundle\Entity\qvGender $genderid = null)
    {
        $this->genderid = $genderid;

        return $this;
    }

    /**
     * Get genderid
     *
     * @return \AppBundle\Entity\qvGender
     */
    public function getGenderid()
    {
        return $this->genderid;
    }

    /**
     * Set userid
     *
     * @param \AppBundle\Entity\qvUser $userid
     *
     * @return qvUserPassport
     */
    public function setUserid(\AppBundle\Entity\qvUser $userid = null)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return \AppBundle\Entity\qvUser
     */
    public function getUserid()
    {
        return $this->userid;
    }
}
