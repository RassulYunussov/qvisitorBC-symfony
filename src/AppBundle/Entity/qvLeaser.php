<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * qvLeaser
 *
 * @ORM\Table(name="qvleaser", 
 * uniqueConstraints={@ORM\UniqueConstraint(name="bin_Leaser_UNIQUE", columns={"bin"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\qvLeaserRepository")
 */
class qvLeaser
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
     * @var string
     *
     * @ORM\Column(name="bin", type="string", length=45, nullable=false)
     */
    private $bin;



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
	 * 
	 * @return string
	 * 
	 * */
	public function __toString()
	{
			return (string)$this->id;
	}
	
    /**
     * Set name
     *
     * @param string $name
     *
     * @return qvLeaser
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
     * Set bin
     *
     * @param string $bin
     *
     * @return qvLeaser
     */
    public function setBin($bin)
    {
        $this->bin = $bin;

        return $this;
    }

    /**
     * Get bin
     *
     * @return string
     */
    public function getBin()
    {
        return $this->bin;
    }
}
