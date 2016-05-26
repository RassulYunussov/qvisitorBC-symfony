<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * qvUser
 *
 * @ORM\Table(name="qvuser", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="id_User_UNIQUE", columns={"id"}), @ORM\UniqueConstraint(name="login_UNIQUE", columns={"login"})}, indexes={@ORM\Index(name="fk_leaser_user_idx", columns={"leaserid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvUserRepository")
 */
class qvUser
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
     * @ORM\Column(name="login", type="string", length=45, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=45, nullable=false)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="disabled", type="integer", nullable=true)
     */
    private $disabled;

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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\qvRole", inversedBy="userid")
     * @ORM\JoinTable(name="rf_user_role",
     *   joinColumns={
     *     @ORM\JoinColumn(name="userid", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="roleid", referencedColumnName="id")
     *   }
     * )
     */
    private $roleid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roleid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set login
     *
     * @param string $login
     *
     * @return qvUser
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return qvUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set disabled
     *
     * @param integer $disabled
     *
     * @return qvUser
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return integer
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * Set leaserid
     *
     * @param \AppBundle\Entity\qvLeaser $leaserid
     *
     * @return qvUser
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
     * Add roleid
     *
     * @param \AppBundle\Entity\qvRole $roleid
     *
     * @return qvUser
     */
    public function addRoleid(\AppBundle\Entity\qvRole $roleid)
    {
        $this->roleid[] = $roleid;

        return $this;
    }

    /**
     * Remove roleid
     *
     * @param \AppBundle\Entity\qvRole $roleid
     */
    public function removeRoleid(\AppBundle\Entity\qvRole $roleid)
    {
        $this->roleid->removeElement($roleid);
    }

    /**
     * Get roleid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoleid()
    {
        return $this->roleid;
    }
}
