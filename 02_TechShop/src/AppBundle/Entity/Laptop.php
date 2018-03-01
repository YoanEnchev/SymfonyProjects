<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Laptop
 *
 * @ORM\Table(name="laptops")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LaptopRepository")
 */
class Laptop
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
     * @ORM\Column(name="processor_frequency", type="integer")
     */
    private $processorFrequency;

    /**
     * @var string
     *
     * @ORM\Column(name="processor_make", type="string", length=255)
     */
    private $processorMake;

    /**
     * @var string
     *
     * @ORM\Column(name="processor_model", type="string", length=255)
     */
    private $processorModel;

    /**
     * @var string
     *
     * @ORM\Column(name="video_card_make", type="string", length=255)
     */
    private $videoCardMake;

    /**
     * @var string
     *
     * @ORM\Column(name="capacity", type="integer")
     */
    private $capacity;

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
     * @var float
     *
     * @ORM\Column(name="weight", type="float")
     */
    private $weight;

    /**
     * @var Product
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function __construct(int $ram, int $processorFrequency, string $processorMake, string $processorModel,
                                string $videoCardMake, int $capacity, int $processorCores, string $operationSystem, int $weight)
    {
        $this->setRam($ram);
        $this->setProcessorFrequency($processorFrequency);
        $this->setProcessorMake($processorMake);
        $this->setProcessorModel($processorModel);
        $this->setVideoCardMake($videoCardMake);
        $this->setCapacity($capacity);
        $this->setProcessorCores($processorCores);
        $this->setOperationSystem($operationSystem);
        $this->setWeight($weight);
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
     * Set id
     *
     * @param integer $id
     *
     * @return Laptop
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set ram
     *
     * @param integer $ram
     *
     * @return Laptop
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
     * Set processorFrequency
     *
     * @param integer $processorFrequency
     *
     * @return Laptop
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
     * Set processorMake
     *
     * @param string $processorMake
     *
     * @return Laptop
     */
    public function setProcessorMake($processorMake)
    {
        $this->processorMake = $processorMake;

        return $this;
    }

    /**
     * Get processorMake
     *
     * @return string
     */
    public function getProcessorMake()
    {
        return $this->processorMake;
    }

    /**
     * Set processorModel
     *
     * @param string $processorModel
     *
     * @return Laptop
     */
    public function setProcessorModel($processorModel)
    {
        $this->processorModel = $processorModel;

        return $this;
    }

    /**
     * Get processorModel
     *
     * @return string
     */
    public function getProcessorModel()
    {
        return $this->processorModel;
    }

    /**
     * Set videoCardMake
     *
     * @param string $videoCardMake
     *
     * @return Laptop
     */
    public function setVideoCardMake($videoCardMake)
    {
        $this->videoCardMake = $videoCardMake;

        return $this;
    }

    /**
     * Get videoCardMake
     *
     * @return string
     */
    public function getVideoCardMake()
    {
        return $this->videoCardMake;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     *
     * @return Laptop
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return integer
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set processorCores
     *
     * @param integer $processorCores
     *
     * @return Laptop
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
     * @return Laptop
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
     * Set weight
     *
     * @param float $weight
     *
     * @return Laptop
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
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

    public function editData(int $ram, int $processorFrequency, string $processorMake, string $processorModel, string $videoCardMake,
                             int $capacity, int $processorCores, string $operationSystem, float $weight)
    {
        $this->setRam($ram);
        $this->setProcessorFrequency($processorFrequency);
        $this->setProcessorMake($processorMake);
        $this->setProcessorModel($processorModel);
        $this->setVideoCardMake($videoCardMake);
        $this->setCapacity($capacity);
        $this->setProcessorCores($processorCores);
        $this->setOperationSystem($operationSystem);
        $this->setWeight($weight);
    }
}

