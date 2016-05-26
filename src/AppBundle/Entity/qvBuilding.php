<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvBuilding
 *
 * @ORM\Table(name="qvbuilding", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_buidling_UNIQUE", columns={"id"})}, 
 * indexes={@ORM\Index(name="fk_businesscenter_building_idx", columns={"businesscenterid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvBuildingRepository")
 */
class qvBuilding
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
     * @var \AppBundle\Entity\qvBusinessCenter
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvBusinessCenter")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="businesscenterid", referencedColumnName="id")
     * })
     */
    private $businesscenterid;



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
     * @return qvBuilding
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
     * Set businesscenterid
     *
     * @param \AppBundle\Entity\qvBusinessCenter $businesscenterid
     *
     * @return qvBuilding
     */
    public function setBusinesscenterid(\AppBundle\Entity\qvBusinessCenter $businesscenterid = null)
    {
        $this->businesscenterid = $businesscenterid;

        return $this;
    }

    /**
     * Get businesscenterid
     *
     * @return \AppBundle\Entity\qvBusinessCenter
     */
    public function getBusinesscenterid()
    {
        return $this->businesscenterid;
    }
}
