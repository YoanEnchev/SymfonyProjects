<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SmartphoneProduct
 *
 * @ORM\Table(name="smartphones")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SmartphoneRepository")
 */
class Smartphone
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
     * @var float
     *
     * @ORM\Column(name="ram", type="float")
     */
    private $ram;

    /**
     * @var string
     *
     * @ORM\Column(name="resolution", type="string", length=255)
     */
    private $resolution;

    /**
     * @var int
     *
     * @ORM\Column(name="front_camera_resolution", type="integer")
     */
    private $frontCameraResolution;

    /**
     * @var int
     *
     * @ORM\Column(name="back_camera_resolution", type="integer")
     */
    private $backCameraResolution;

    /**
     * @var float
     *
     * @ORM\Column(name="screen_diagonal_size", type="float")
     */
    private $screenDiagonalSize;

    /**
     * @var float
     *
     * @ORM\Column(name="memory", type="float")
     */
    private $memory;

    /**
     * @var int
     *
     * @ORM\Column(name="processor_frequency", type="integer")
     */
    private $processorFrequency;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @var Product
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function __construct(int $ram, string $resolution, int $frontCameraResolution, int $backCameraResolution,
                                int $screenDiagonalSize, int $memory, int $processorFrequency, string $color)
    {
        $this->setRAM($ram);
        $this->setResolution($resolution);
        $this->setFrontCameraResolution($frontCameraResolution);
        $this->setBackCameraResolution($backCameraResolution);
        $this->setScreenDiagonalSize($screenDiagonalSize);
        $this->setMemory($memory);
        $this->setProcessorFrequency($processorFrequency);
        $this->setColor($color);
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
     * @param float $ram
     *
     * @return Smartphone
     */
    public function setRAM($ram)
    {
        $this->ram = $ram;

        return $this;
    }

    /**
     * Get ram
     *
     * @return float
     */
    public function getRAM()
    {
        return $this->ram;
    }

    /**
     * Set resolution
     *
     * @param string $resolution
     *
     * @return Smartphone
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set frontCameraResolution
     *
     * @param integer $frontCameraResolution
     *
     * @return Smartphone
     */
    public function setFrontCameraResolution($frontCameraResolution)
    {
        $this->frontCameraResolution = $frontCameraResolution;

        return $this;
    }

    /**
     * Get frontCameraResolution
     *
     * @return int
     */
    public function getFrontCameraResolution()
    {
        return $this->frontCameraResolution;
    }

    /**
     * Set backCameraResolution
     *
     * @param integer $backCameraResolution
     *
     * @return Smartphone
     */
    public function setBackCameraResolution($backCameraResolution)
    {
        $this->backCameraResolution = $backCameraResolution;

        return $this;
    }

    /**
     * Get backCameraResolution
     *
     * @return int
     */
    public function getBackCameraResolution()
    {
        return $this->backCameraResolution;
    }

    /**
     * Set screenDiagonalSize
     *
     * @param float $screenDiagonalSize
     *
     * @return Smartphone
     */
    public function setScreenDiagonalSize($screenDiagonalSize)
    {
        $this->screenDiagonalSize = $screenDiagonalSize;

        return $this;
    }

    /**
     * Get screenDiagonalSize
     *
     * @return float
     */
    public function getScreenDiagonalSize()
    {
        return $this->screenDiagonalSize;
    }

    /**
     * Set memory
     *
     * @param float $memory
     *
     * @return Smartphone
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * Get memory
     *
     * @return float
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * Set processorFrequency
     *
     * @param integer $processorFrequency
     *
     * @return Smartphone
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
     * Set color
     *
     * @param string $color
     *
     * @return Smartphone
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
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
     *
     * @return Smartphone
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }
}

