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
    private $floor;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\qvContract", mappedBy="sectorid")
     */
    private $contracts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contracts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set floor
     *
     * @param \AppBundle\Entity\qvFloor $floor
     *
     * @return qvSector
     */
    public function setFloor(\AppBundle\Entity\qvFloor $floor = null)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * Get floor
     *
     * @return \AppBundle\Entity\qvFloor
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Add contract
     *
     * @param \AppBundle\Entity\qvContract $contract
     *
     * @return qvSector
     */
    public function addContractid(\AppBundle\Entity\qvContract $contract)
    {
        $this->contracts[] = $contract;

        return $this;
    }

    /**
     * Remove contract
     *
     * @param \AppBundle\Entity\qvContract $contract
     */
    public function removeContract(\AppBundle\Entity\qvContract $contract)
    {
        $this->contracts->removeElement($contract);
    }

    /**
     * Get contracts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContractid()
    {
        return $this->contracts;
    }
}
