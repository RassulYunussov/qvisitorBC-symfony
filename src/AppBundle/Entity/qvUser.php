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
    private $leaser;

     /**
     * @var \AppBundle\Entity\qvRole
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\qvRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="roleid", referencedColumnName="id")
     * })
     */
    private $role;

    /**
     * Constructor
     */
    public function __construct()
    {
      
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
     * toString login
     * 
     * @return string
     */
    public function __toString() 
	{
    	return  $this->login;
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
     * Set leaser
     *
     * @param \AppBundle\Entity\qvLeaser $leaser
     *
     * @return qvUser
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
     * Set role
     *
     * @param \AppBundle\Entity\qvRole $role
     *
     * @return qvUser
     */
    public function setRole(\AppBundle\Entity\qvRole $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\qvRole
     */
    public function getRole()
    {
        return $this->role;
    }
}
