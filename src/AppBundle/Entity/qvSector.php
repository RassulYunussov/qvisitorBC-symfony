<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvSector
 *
 * @ORM\Table(name="qvsector", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_Sector_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_floor_sector_idx", columns={"floorid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvSectorRepository")
 */
class qvSector
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
     * @var \AppBundle\Entity\qvFloor
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvFloor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="floorid", referencedColumnName="id")
     * })
     */
    private $floorid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\qvContract", mappedBy="sectorid")
     */
    private $contractid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contractid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return qvSector
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
     * Set floorid
     *
     * @param \AppBundle\Entity\qvFloor $floorid
     *
     * @return qvSector
     */
    public function setFloorid(\AppBundle\Entity\qvFloor $floorid = null)
    {
        $this->floorid = $floorid;

        return $this;
    }

    /**
     * Get floorid
     *
     * @return \AppBundle\Entity\qvFloor
     */
    public function getFloorid()
    {
        return $this->floorid;
    }

    /**
     * Add contractid
     *
     * @param \AppBundle\Entity\qvContract $contractid
     *
     * @return qvSector
     */
    public function addContractid(\AppBundle\Entity\qvContract $contractid)
    {
        $this->contractid[] = $contractid;

        return $this;
    }

    /**
     * Remove contractid
     *
     * @param \AppBundle\Entity\qvContract $contractid
     */
    public function removeContractid(\AppBundle\Entity\qvContract $contractid)
    {
        $this->contractid->removeElement($contractid);
    }

    /**
     * Get contractid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContractid()
    {
        return $this->contractid;
    }
}
