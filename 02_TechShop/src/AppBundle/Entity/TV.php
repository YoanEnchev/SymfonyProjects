<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TV
 *
 * @ORM\Table(name="tvs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TVRepository")
 */
class TV
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
     * @ORM\Column(name="screen_diagonal_size", type="float")
     */
    private $screenDiagonalSize;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_smart", type="boolean")
     */
    private $isSmart;

    /**
     * @var bool
     *
     * @ORM\Column(name="has_usb_port", type="boolean")
     */
    private $hasUSBPort;

    /**
     * @var string
     *
     * @ORM\Column(name="resolution", type="string", length=255)
     */
    private $resolution;

    /**
     * @var float
     *
     * @ORM\Column(name="power_consummation", type="float")
     */
    private $powerConsummation;

    /**
     * @var float
     *
     * @ORM\Column(name="weight", type="float")
     */
    private $weight;

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

    public function __construct(float $screenDiagonalSize, bool $isSmart, bool $hasUSBPort, string $resolution,
                                int $powerConsummation, int $weight, string $color)
    {
        $this->setScreenDiagonalSize($screenDiagonalSize);
        $this->setIsSmart($isSmart);
        $this->setHasUSBPort($hasUSBPort);
        $this->setResolution($resolution);
        $this->setPowerConsummation($powerConsummation);
        $this->setWeight($weight);
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
     * Set screenDiagonalSize
     *
     * @param float $screenDiagonalSize
     *
     * @return TV
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
     * Set isSmart
     *
     * @param boolean $isSmart
     *
     * @return TV
     */
    public function setIsSmart($isSmart)
    {
        $this->isSmart = $isSmart;

        return $this;
    }

    /**
     * Get isSmart
     *
     * @return bool
     */
    public function getIsSmart()
    {
        return $this->isSmart;
    }

    /**
     * Set hasUSBPort
     *
     * @param boolean $hasUSBPort
     *
     * @return TV
     */
    public function setHasUSBPort($hasUSBPort)
    {
        $this->hasUSBPort = $hasUSBPort;

        return $this;
    }

    /**
     * Get hasUSBPort
     *
     * @return bool
     */
    public function getHasUSBPort()
    {
        return $this->hasUSBPort;
    }

    /**
     * Set resolution
     *
     * @param string $resolution
     *
     * @return TV
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
     * Set powerConsummation
     *
     * @param float $powerConsummation
     *
     * @return TV
     */
    public function setPowerConsummation($powerConsummation)
    {
        $this->powerConsummation = $powerConsummation;

        return $this;
    }

    /**
     * Get powerConsummation
     *
     * @return float
     */
    public function getPowerConsummation()
    {
        return $this->powerConsummation;
    }

    /**
     * Set weight
     *
     * @param float $weight
     *
     * @return TV
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
     * Set color
     *
     * @param string $color
     *
     * @return TV
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
     * @return TV
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }
}

