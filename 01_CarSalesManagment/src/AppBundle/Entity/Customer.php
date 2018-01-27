<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="datetime")
     */
    private $birthDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_young_driver", type="boolean")
     */
    private $isYoungDriver;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sale", mappedBy="customer")
     */
    private $sales;

    /**
     * Get id
     *
     * @return int
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
     * @return Customer
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
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Customer
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set isYoungDriver
     *
     * @param boolean $isYoungDriver
     *
     * @return Customer
     */
    public function setIsYoungDriver($isYoungDriver)
    {
        $this->isYoungDriver = $isYoungDriver;

        return $this;
    }

    /**
     * Get isYoungDriver
     *
     * @return bool
     */
    public function getIsYoungDriver()
    {
        return $this->isYoungDriver;
    }
}

