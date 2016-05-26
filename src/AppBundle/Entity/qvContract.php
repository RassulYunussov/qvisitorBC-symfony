<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvContract
 *
 * @ORM\Table(name="qvcontract", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_Contract_UNIQUE", columns={"id"})}, 
 * indexes={@ORM\Index(name="fk_leaser_contract_idx", columns={"leaserid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvContractRepository")
 */
class qvContract
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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="datetime", nullable=false)
     */
    private $startdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="datetime", nullable=false)
     */
    private $enddate;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\qvSector", inversedBy="contractid")
     * @ORM\JoinTable(name="rf_contract_sector",
     *   joinColumns={
     *     @ORM\JoinColumn(name="contractid", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="sectorid", referencedColumnName="id")
     *   }
     * )
     */
    private $sectorid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sectorid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return qvContract
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set startdate
     *
     * @param \DateTime $startdate
     *
     * @return qvContract
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate
     *
     * @param \DateTime $enddate
     *
     * @return qvContract
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * Get enddate
     *
     * @return \DateTime
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set leaserid
     *
     * @param \AppBundle\Entity\qvLeaser $leaserid
     *
     * @return qvContract
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
     * Add sectorid
     *
     * @param \AppBundle\Entity\qvSector $sectorid
     *
     * @return qvContract
     */
    public function addSectorid(\AppBundle\Entity\qvSector $sectorid)
    {
        $this->sectorid[] = $sectorid;

        return $this;
    }

    /**
     * Remove sectorid
     *
     * @param \AppBundle\Entity\qvSector $sectorid
     */
    public function removeSectorid(\AppBundle\Entity\qvSector $sectorid)
    {
        $this->sectorid->removeElement($sectorid);
    }

    /**
     * Get sectorid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSectorid()
    {
        return $this->sectorid;
    }
}
