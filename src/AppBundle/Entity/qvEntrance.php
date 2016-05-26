<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvEntrance
 *
 * @ORM\Table(name="qventrance", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_Entrance_UNIQUE", columns={"id"})}, 
 * indexes={@ORM\Index(name="fk_checkpoint_entrance_idx", columns={"checkpointid"}), @ORM\Index(name="fk_order_entrance_idx", columns={"orderid"}), @ORM\Index(name="fk_user_entrance_idx", columns={"userid"}), @ORM\Index(name="fk_visitor_entrance_idx", columns={"visitorid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvEntranceRepository")
 */
class qvEntrance
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
     * @var \AppBundle\Entity\qvOrder
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvOrder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="orderid", referencedColumnName="id")
     * })
     */
    private $orderid;

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
     * Set entrancedate
     *
     * @param \DateTime $entrancedate
     *
     * @return qvEntrance
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
     * @return qvEntrance
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
     * Set orderid
     *
     * @param \AppBundle\Entity\qvOrder $orderid
     *
     * @return qvEntrance
     */
    public function setOrderid(\AppBundle\Entity\qvOrder $orderid = null)
    {
        $this->orderid = $orderid;

        return $this;
    }

    /**
     * Get orderid
     *
     * @return \AppBundle\Entity\qvOrder
     */
    public function getOrderid()
    {
        return $this->orderid;
    }

    /**
     * Set userid
     *
     * @param \AppBundle\Entity\qvUser $userid
     *
     * @return qvEntrance
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

    /**
     * Set visitorid
     *
     * @param \AppBundle\Entity\qvVisitor $visitorid
     *
     * @return qvEntrance
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
