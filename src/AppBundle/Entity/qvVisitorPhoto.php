<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvVisitorPhoto
 *
 * @ORM\Table(name="qvvisitorphoto", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_VisitorPhoto_UNIQUE", columns={"id"})}, 
 * indexes={@ORM\Index(name="fk_visitor_visitorphoto_idx", columns={"visitorid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvVisitorPhotoRepository")
 */
class qvVisitorPhoto
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
     * @ORM\Column(name="photo", type="blob", length=65535, nullable=false)
     */
    private $photo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="photodate", type="datetime", nullable=false)
     */
    private $photodate;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return qvVisitorPhoto
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set photodate
     *
     * @param \DateTime $photodate
     *
     * @return qvVisitorPhoto
     */
    public function setPhotodate($photodate)
    {
        $this->photodate = $photodate;

        return $this;
    }

    /**
     * Get photodate
     *
     * @return \DateTime
     */
    public function getPhotodate()
    {
        return $this->photodate;
    }

    /**
     * Set visitor
     *
     * @param \AppBundle\Entity\qvVisitor $visitor
     *
     * @return qvVisitorPhoto
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
