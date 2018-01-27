<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Supplier
 *
 * @ORM\Table(name="suppliers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SupplierRepository")
 */
class Supplier
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
     * @var bool
     *
     * @ORM\Column(name="is_importer", type="boolean")
     */
    private $isImporter;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Part", mappedBy="parts")
     */
    private $parts;

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
     * @return Supplier
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
     * Set isImporter
     *
     * @param boolean $isImporter
     *
     * @return Supplier
     */
    public function setIsImporter($isImporter)
    {
        $this->isImporter = $isImporter;

        return $this;
    }

    /**
     * Get isImporter
     *
     * @return bool
     */
    public function getIsImporter()
    {
        return $this->isImporter;
    }

    /**
     * Get Parts
     *
     * @return Part
     */
    public function getParts()
    {
        return $this->parts;
    }
}

