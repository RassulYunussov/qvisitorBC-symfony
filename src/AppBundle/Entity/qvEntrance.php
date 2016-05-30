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
    private $checkpoint;

    /**
     * @var \AppBundle\Entity\qvOrder
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvOrder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="orderid", referencedColumnName="id")
     * })
     */
    private $order;

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
     * @var \AppBundle\Entity\qvVisitor
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvVisitor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visitorid", referencedColumnName="id")
     * })
     */
    private $visitor;
   /**
    * @var string
    * 
    * 
    *  
    */
    
    /**
     * toString name
     * 
     * return string
     */
    public function __toString() 
	{
    	return  $this->visitor;
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
     * Set checkpoint
     *
     * @param \AppBundle\Entity\qvCheckpoint $checkpoint
     *
     * @return qvEntrance
     */
    public function setCheckpoint(\AppBundle\Entity\qvCheckpoint $checkpoint = null)
    {
        $this->checkpoint = $checkpoint;

        return $this;
    }

    /**
     * Get checkpoint
     *
     * @return \AppBundle\Entity\qvCheckpoint
     */
    public function getCheckpoint()
    {
        return $this->checkpoint;
    }
    
    /**
     * Set order
     *
     * @param \AppBundle\Entity\qvOrder $order
     *
     * @return qvEntrance
     */
    public function setOrder(\AppBundle\Entity\qvOrder $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\qvOrder
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\qvUser $user
     *
     * @return qvEntrance
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
     * Set visitor
     *
     * @param \AppBundle\Entity\qvVisitor $visitor
     *
     * @return qvEntrance
     */
    public function setVisitor(\AppBundle\Entity\qvVisitor $visitor = null)
    {
        $this->visitor = $visitor;

        return $this;
    }

    
    /**
     * Get visitor
     *
     * @return \AppBundle\Entity\qvVisitor
     */
    public function getVisitor()
    {
        return $this->visitor;
    }
}
