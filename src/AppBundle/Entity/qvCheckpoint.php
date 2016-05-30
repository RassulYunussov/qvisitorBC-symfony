<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvCheckpoint
 *
 * @ORM\Table(name="qvcheckpoint", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_Checkpoint_UNIQUE", columns={"id"})}, 
 * indexes={@ORM\Index(name="fk_building_checkpoint_idx", columns={"buildingid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvCheckpointRepository")
 */
class qvCheckpoint
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
     * @var \AppBundle\Entity\qvBuilding
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvBuilding")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="buildingid", referencedColumnName="id")
     * })
     */
    private $building;



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
     * @return qvCheckpoint
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
     * Set building
     *
     * @param \AppBundle\Entity\qvBuilding $building
     *
     * @return qvCheckpoint
     */
    public function setBuilding(\AppBundle\Entity\qvBuilding $building = null)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building
     *
     * @return \AppBundle\Entity\qvBuilding
     */
    public function getBuilding()
    {
        return $this->building;
    }
   
    public function __toString()
    {
    	return $this->name;
    }
    
}
