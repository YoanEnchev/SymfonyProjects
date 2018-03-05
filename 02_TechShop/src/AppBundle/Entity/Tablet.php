<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tablet
 *
 * @ORM\Table(name="tablets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TabletRepository")
 */
class Tablet
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
     * @var int
     *
     * @ORM\Column(name="ram", type="integer")
     */
    private $ram;

    /**
     * @var int
     *
     * @ORM\Column(name="capacity", type="integer")
     */
    private $capacity;

    /**
     * @var float
     *
     * @ORM\Column(name="display_diagonal", type="float")
     */
    private $displayDiagonal;

    /**
     * @var int
     *
     * @ORM\Column(name="processor_frequency", type="integer")
     */
    private $processorFrequency;

    /**
     * @var int
     *
     * @ORM\Column(name="processor_cores", type="integer")
     */
    private $processorCores;

    /**
     * @var string
     *
     * @ORM\Column(name="operation_system", type="string", length=255)
     */
    private $operationSystem;

    /**
     * @var Product
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;


    public function __construct(int $ram, int $capacity, float $displayDiagonal, int $processorFrequency, int $processorCores, string $operationSystem)
    {
        $this->setRam($ram);
        $this->setCapacity($capacity);
        $this->setDisplayDiagonal($displayDiagonal);
        $this->setProcessorFrequency($processorFrequency);
        $this->setProcessorCores($processorCores);
        $this->setOperationSystem($operationSystem);
    }

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
     * Set ram
     *
     * @param integer $ram
     *
     * @return Tablet
     */
    public function setRam($ram)
    {
        $this->ram = $ram;

        return $this;
    }

    /**
     * Get ram
     *
     * @return int
     */
    public function getRam()
    {
        return $this->ram;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     *
     * @return Tablet
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return int
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set displayDiagonal
     *
     * @param float $displayDiagonal
     *
     * @return Tablet
     */
    public function setDisplayDiagonal($displayDiagonal)
    {
        $this->displayDiagonal = $displayDiagonal;

        return $this;
    }

    /**
     * Get displayDiagonal
     *
     * @return float
     */
    public function getDisplayDiagonal()
    {
        return $this->displayDiagonal;
    }

    /**
     * Set processorFrequency
     *
     * @param integer $processorFrequency
     *
     * @return Tablet
     */
    public function setProcessorFrequency($processorFrequency)
    {
        $this->processorFrequency = $processorFrequency;

        return $this;
    }

    /**
     * Get processorFrequency
     *
     * @return int
     */
    public function getProcessorFrequency()
    {
        return $this->processorFrequency;
    }

    /**
     * Set processorCores
     *
     * @param integer $processorCores
     *
     * @return Tablet
     */
    public function setProcessorCores($processorCores)
    {
        $this->processorCores = $processorCores;

        return $this;
    }

    /**
     * Get processorCores
     *
     * @return int
     */
    public function getProcessorCores()
    {
        return $this->processorCores;
    }

    /**
     * Set operationSystem
     *
     * @param string $operationSystem
     *
     * @return Tablet
     */
    public function setOperationSystem($operationSystem)
    {
        $this->operationSystem = $operationSystem;

        return $this;
    }

    /**
     * Get operationSystem
     *
     * @return string
     */
    public function getOperationSystem()
    {
        return $this->operationSystem;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function editData(int $ram, int $capacity, int $displayDiagonal, int $processorFrequency, int $processorCores,
    string $operationSystem)
    {
        $this->setRam($ram);
        $this->setCapacity($capacity);
        $this->setDisplayDiagonal($displayDiagonal);
        $this->setProcessorFrequency($processorFrequency);
        $this->setProcessorCores($processorCores);
        $this->setOperationSystem($operationSystem);
    }
}

