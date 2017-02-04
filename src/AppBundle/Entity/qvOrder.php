<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    private $ordertype;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\qvVisitor", mappedBy="orders")
     */
    private $visitors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visitors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get str
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
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
     * Set ordertype
     *
     * @param \AppBundle\Entity\qvOrderType $ordertype
     *
     * @return qvOrder
     */
    public function setOrdertype(\AppBundle\Entity\qvOrderType $ordertype = null)
    {
        $this->ordertype = $ordertype;

        return $this;
    }

    /**
     * Get ordertype
     *
     * @return \AppBundle\Entity\qvOrderType
     */
    public function getOrdertype()
    {
        return $this->ordertype;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\qvUser $user
     *
     * @return qvOrder
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

    /**
     * Add visitors
     *
     * @param \AppBundle\Entity\qvVisitor $visitors
     *
     * @return qvOrder
     */
    public function addVisitors(\AppBundle\Entity\qvVisitor $visitors)
    {
        $this->visitors[] = $visitors;

        return $this;
    }

    /**
     * Remove visitors
     *
     * @param \AppBundle\Entity\qvVisitor $visitors
     */
    public function removeVisitors(\AppBundle\Entity\qvVisitor $visitors)
    {
        $this->visitors->removeElement($visitors);
    }

    /**
     * Get visitors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisitors()
    {
        return $this->visitors;
    }
}
