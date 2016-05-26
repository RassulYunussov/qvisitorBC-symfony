<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvHotEntrance
 *
 * @ORM\Table(name="qvhotentrance", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_HotEntrance_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_checkpoint_hotentrance_idx", columns={"checkpointid"}), @ORM\Index(name="fk_leaser_hotentrance_idx", columns={"leaserid"}), @ORM\Index(name="fk_user_hotentrance_idx", columns={"userid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvHotEntranceRepository")
 */
class qvHotEntrance
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
     * @var string
     *
     * @ORM\Column(name="documentnumber", type="string", length=45, nullable=false)
     */
    private $documentnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="organization", type="string", length=45, nullable=true)
     */
    private $organization;

    /**
     * @var string
     *
     * @ORM\Column(name="attendant", type="string", length=45, nullable=true)
     */
    private $attendant;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=45, nullable=true)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="entrancedate", type="datetime", nullable=false)
     */
    private $entrancedate;

    /**
     * @var \AppBundle\Entity\qvCheckpoint
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvCheckpoint")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="checkpointid", referencedColumnName="id")
     * })
     */
    private $checkpointid;

    /**
     * @var \AppBundle\Entity\qvLeaser
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvLeaser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="leaserid", referencedColumnName="id")
     * })
     */
    private $leaserid;

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
     * @return qvHotEntrance
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
     * @return qvHotEntrance
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
     * @return qvHotEntrance
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
     * Set documentnumber
     *
     * @param string $documentnumber
     *
     * @return qvHotEntrance
     */
    public function setDocumentnumber($documentnumber)
    {
        $this->documentnumber = $documentnumber;

        return $this;
    }

    /**
     * Get documentnumber
     *
     * @return string
     */
    public function getDocumentnumber()
    {
        return $this->documentnumber;
    }

    /**
     * Set organization
     *
     * @param string $organization
     *
     * @return qvHotEntrance
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set attendant
     *
     * @param string $attendant
     *
     * @return qvHotEntrance
     */
    public function setAttendant($attendant)
    {
        $this->attendant = $attendant;

        return $this;
    }

    /**
     * Get attendant
     *
     * @return string
     */
    public function getAttendant()
    {
        return $this->attendant;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return qvHotEntrance
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set entrancedate
     *
     * @param \DateTime $entrancedate
     *
     * @return qvHotEntrance
     */
    public function setEntrancedate($entrancedate)
    {
        $this->entrancedate = $entrancedate;

        return $this;
    }

    /**
     * Get entrancedate
     *
     * @return \DateTime
     */
    public function getEntrancedate()
    {
        return $this->entrancedate;
    }

    /**
     * Set checkpointid
     *
     * @param \AppBundle\Entity\qvCheckpoint $checkpointid
     *
     * @return qvHotEntrance
     */
    public function setCheckpointid(\AppBundle\Entity\qvCheckpoint $checkpointid = null)
    {
        $this->checkpointid = $checkpointid;

        return $this;
    }

    /**
     * Get checkpointid
     *
     * @return \AppBundle\Entity\qvCheckpoint
     */
    public function getCheckpointid()
    {
        return $this->checkpointid;
    }

    /**
     * Set leaserid
     *
     * @param \AppBundle\Entity\qvLeaser $leaserid
     *
     * @return qvHotEntrance
     */
    public function setLeaserid(\AppBundle\Entity\qvLeaser $leaserid = null)
    {
        $this->leaserid = $leaserid;

        return $this;
    }

    /**
     * Get leaserid
     *
     * @return \AppBundle\Entity\qvLeaser
     */
    public function getLeaserid()
    {
        return $this->leaserid;
    }

    /**
     * Set userid
     *
     * @param \AppBundle\Entity\qvUser $userid
     *
     * @return qvHotEntrance
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
