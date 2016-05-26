<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvOrder
 *
 * @ORM\Table(name="qvorder", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_Order_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_user_order_idx", columns={"userid"}), @ORM\Index(name="fk_ordertype_order_idx", columns={"ordertypeid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvOrderRepository")
 */
class qvOrder
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
     * @ORM\Column(name="sdate", type="datetime", nullable=false)
     */
    private $sdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="edate", type="datetime", nullable=false)
     */
    private $edate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="opentime", type="datetime", nullable=false)
     */
    private $opentime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closetime", type="datetime", nullable=false)
     */
    private $closetime;

    /**
     * @var \AppBundle\Entity\qvOrderType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvOrderType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ordertypeid", referencedColumnName="id")
     * })
     */
    private $ordertypeid;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\qvVisitor", mappedBy="orderid")
     */
    private $visitorid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visitorid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set sdate
     *
     * @param \DateTime $sdate
     *
     * @return qvOrder
     */
    public function setSdate($sdate)
    {
        $this->sdate = $sdate;

        return $this;
    }

    /**
     * Get sdate
     *
     * @return \DateTime
     */
    public function getSdate()
    {
        return $this->sdate;
    }

    /**
     * Set edate
     *
     * @param \DateTime $edate
     *
     * @return qvOrder
     */
    public function setEdate($edate)
    {
        $this->edate = $edate;

        return $this;
    }

    /**
     * Get edate
     *
     * @return \DateTime
     */
    public function getEdate()
    {
        return $this->edate;
    }

    /**
     * Set opentime
     *
     * @param \DateTime $opentime
     *
     * @return qvOrder
     */
    public function setOpentime($opentime)
    {
        $this->opentime = $opentime;

        return $this;
    }

    /**
     * Get opentime
     *
     * @return \DateTime
     */
    public function getOpentime()
    {
        return $this->opentime;
    }

    /**
     * Set closetime
     *
     * @param \DateTime $closetime
     *
     * @return qvOrder
     */
    public function setClosetime($closetime)
    {
        $this->closetime = $closetime;

        return $this;
    }

    /**
     * Get closetime
     *
     * @return \DateTime
     */
    public function getClosetime()
    {
        return $this->closetime;
    }

    /**
     * Set ordertypeid
     *
     * @param \AppBundle\Entity\qvOrderType $ordertypeid
     *
     * @return qvOrder
     */
    public function setOrdertypeid(\AppBundle\Entity\qvOrderType $ordertypeid = null)
    {
        $this->ordertypeid = $ordertypeid;

        return $this;
    }

    /**
     * Get ordertypeid
     *
     * @return \AppBundle\Entity\qvOrderType
     */
    public function getOrdertypeid()
    {
        return $this->ordertypeid;
    }

    /**
     * Set userid
     *
     * @param \AppBundle\Entity\qvUser $userid
     *
     * @return qvOrder
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
     * Add visitorid
     *
     * @param \AppBundle\Entity\qvVisitor $visitorid
     *
     * @return qvOrder
     */
    public function addVisitorid(\AppBundle\Entity\qvVisitor $visitorid)
    {
        $this->visitorid[] = $visitorid;

        return $this;
    }

    /**
     * Remove visitorid
     *
     * @param \AppBundle\Entity\qvVisitor $visitorid
     */
    public function removeVisitorid(\AppBundle\Entity\qvVisitor $visitorid)
    {
        $this->visitorid->removeElement($visitorid);
    }

    /**
     * Get visitorid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitorid()
    {
        return $this->visitorid;
    }
}
