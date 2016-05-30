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
    private $leaser;

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
    private $sectors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sectors = new \Doctrine\Common\Collections\ArrayCollection();
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
        return $this-> startdate;
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
        return $this-> enddate;
    }

    /**
     * Set leaser
     *
     * @param \AppBundle\Entity\qvLeaser $leaser
     *
     * @return qvContract
     */
    public function setLeaser(\AppBundle\Entity\qvLeaser $leaser = null)
    {
        $this->leaser = $leaser;

        return $this;
    }

    /**
     * Get leaser
     *
     * @return \AppBundle\Entity\qvLeaser
     */
    public function getLeaser()
    {
        return $this->leaser;
    }

    /**
     * Add sectors
     *
     * @param \AppBundle\Entity\qvSector $sectors
     *
     * @return qvContract
     */
    public function addSectors(\AppBundle\Entity\qvSector $sectors)
    {
        $this->sectors[] = $sectors;

        return $this;
    }

    /**
     * Remove sectors
     *
     * @param \AppBundle\Entity\qvSector $sectors
     */
    public function removeSectors(\AppBundle\Entity\qvSector $sectors)
    {
        $this->sectors->removeElement($sectors);
    }

    /**
     * Get sectors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSectors()
    {
        return $this->sectors;
    }
}
