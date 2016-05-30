<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvUserPhoto
 *
 * @ORM\Table(name="qvuserphoto", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_UserPhoto_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_user_userphoto_idx", columns={"userid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvUserPhotoRepository")
 */
class qvUserPhoto
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
     * @var \AppBundle\Entity\qvUser
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userid", referencedColumnName="id")
     * })
     */
    private $user;



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
     * @return qvUserPhoto
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
     * @return qvUserPhoto
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
     * Set user
     *
     * @param \AppBundle\Entity\qvUser $user
     *
     * @return qvUserPhoto
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
}
