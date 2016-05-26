<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $buildingid;



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
     * @param \AppBundle\Entity\qvBuilding $buildingid
     *
     * @return qvFloor
     */
    public function setBuildingid(\AppBundle\Entity\qvBuilding $buildingid = null)
    {
        $this->buildingid = $buildingid;

        return $this;
    }

    /**
     * Get buildingid
     *
     * @return \AppBundle\Entity\qvBuilding
     */
    public function getBuildingid()
    {
        return $this->buildingid;
    }
}
