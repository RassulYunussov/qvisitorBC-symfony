<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * qvFloor
 *
 * @ORM\Table(name="qvfloor", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_Floor_UNIQUE", columns={"id"})}, 
 * indexes={@ORM\Index(name="fk_building_floor_idx", columns={"buildingid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvFloorRepository")
 */
class qvFloor
{
	public function __construct() {
		$this->sectors = new ArrayCollection();
	}
	
	
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
     * @ORM\Column(name="name", type="string", length=2, nullable=true)
     */
    private $name;

    /**
     * @var \AppBundle\Entity\qvBuilding
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvBuilding")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="buildingid", referencedColumnName="id")
     * })
     */
    private $building;
    
    // ...
    /**
     * One Floor has Many Sectors.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\qvSector", mappedBy="floor")
     */
    private $sectors;
    // ...
    


    public function __toString()
    {
    	return $this->name;
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
     * @return qvFloor
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
     * Set buildingid
     *
     * @param \AppBundle\Entity\qvBuilding $building
     *
     * @return qvFloor
     */
    public function setBuilding(\AppBundle\Entity\qvBuilding $building = null)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get buildingid
     *
     * @return \AppBundle\Entity\qvBuilding
     */
    public function getBuilding()
    {
        return $this->building;
    }
    
    public function getSectors()
    {
    	return $this->sectors;
    }
}
