<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

class qvUserAccountAccount implements UserInterface
{
    /**
     * @var string
     *
     */
    private $login;
    /**
     *
     * @var string
     */
    private $password;
    
     /**
     * @var string
     *
     */
    private $role;
    /**
     * @var string
     *
     * 
     */
 private $firstname;
    /**
     * @var string
     *
     * 
     */
    private $lastname;
    /**
     * @var string
     *
     * 
     */
    private $patronimic;
    /**
     * @var \DateTime
     *
     * 
     */
    private $birthdate;
    /**
     * @var \AppBundle\Entity\qvGender
     *
     */
    private $gender;
    /**
     * @var \AppBundle\Entity\qvUserAccount
     *
     * 
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        // may not be needed, see section on salt below
        $this->salt = md5(uniqid(null, true));
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return qvUserAccount
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
     * @return qvUserAccount
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
     * Set role
     *
     * @param \AppBundle\Entity\qvRole $role
     *
     * @return qvUserAccount
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

    public function getRoles()
    {
        return array($this->role->getCode());
    }
    
    public function eraseCredentials()
    {
    }
    public function getUsername()
    {
        return $this->login;
    }
    
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }
    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return qvUserAccount
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }
    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
    public function __toString()
    {
        return $this->firstname;
    }
    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return qvUserAccount
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }
    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }
    /**
     * Set patronimic
     *
     * @param string $patronimic
     *
     * @return qvUserAccount
     */
    public function setPatronimic($patronimic)
    {
        $this->patronimic = $patronimic;
        return $this;
    }
    /**
     * Get patronimic
     *
     * @return string
     */
    public function getPatronimic()
    {
        return $this->patronimic;
    }
    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return qvUserAccount
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }
    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }
    /**
     * Set gender
     *
     * @param \AppBundle\Entity\qvGender $gender
     *
     * @return qvUserAccount
     */
    public function setGender(\AppBundle\Entity\qvGender $gender = null)
    {
        $this->gender = $gender;
        return $this;
    }
    /**
     * Get gender
     *
     * @return \AppBundle\Entity\qvGender
     */
    public function getGender()
    {
        return $this->gender;
    }
    /**
     * Set user
     *
     * @param \AppBundle\Entity\qvUser $user
     *
     * @return qvUserAccount
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
    