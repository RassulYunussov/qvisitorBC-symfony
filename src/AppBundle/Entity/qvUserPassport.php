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
    private $gender;

    /**
     * @var \AppBundle\Entity\qvUser
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userid", referencedColumnName="id")
     * })
     */
    private $user;



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
    public function __toString()
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
     * Set gender
     *
     * @param \AppBundle\Entity\qvGender $gender
     *
     * @return qvUserPassport
     */
    public function setGender(\AppBundle\Entity\qvGender $gender = null)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return \AppBundle\Entity\qvGender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\qvUser $user
     *
     * @return qvUserPassport
     */
    public function setUser(\AppBundle\Entity\qvUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\qvUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
