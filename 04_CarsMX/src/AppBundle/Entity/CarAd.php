<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarAd
 *
 * @ORM\Table(name="car_ads")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarAdRepository")
 */
class CarAd
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
     * @ORM\Column(name="make", type="string", length=255)
     */
    private $make;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="modification", type="string", length=255)
     */
    private $modification;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="transmission", type="string", length=255)
     */
    private $transmission;

    /**
     * @var string
     *
     * @ORM\Column(name="fuel", type="string", length=255)
     */
    private $fuel;

    /**
     * @var int
     *
     * @ORM\Column(name="engine_power", type="integer")
     */
    private $enginePower;

    /**
     * @var int
     *
     * @ORM\Column(name="cubic_capacity", type="integer")
     */
    private $cubicCapacity;

    /**
     * @var int
     *
     * @ORM\Column(name="manufacture_year", type="integer")
     */
    private $manufactureYear;

    /**
     * @var int
     *
     * @ORM\Column(name="mileage", type="integer")
     */
    private $mileage;

    /**
     * @var string
     *
     * @ORM\Column(name="doors", type="string", length=255)
     */
    private $doors;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="main_image", type="string", length=255)
     */
    private $mainImage;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=255)
     */
    private $description;

    /**
     * @var \datetime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * Many carAds have One User.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="carAds")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct()
    {
        $this->setDateAdded(new \DateTime("now"));
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
     * Set make
     *
     * @param string $make
     *
     * @return CarAd
     */
    public function setMake($make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * Get make
     *
     * @return string
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return CarAd
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set modification
     *
     * @param string $modification
     *
     * @return CarAd
     */
    public function setModification($modification)
    {
        $this->modification = $modification;

        return $this;
    }

    /**
     * Get modification
     *
     * @return string
     */
    public function getModification()
    {
        return $this->modification;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return CarAd
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set transmission
     *
     * @param string $transmission
     *
     * @return CarAd
     */
    public function setTransmission($transmission)
    {
        $this->transmission = $transmission;

        return $this;
    }

    /**
     * Get transmission
     *
     * @return string
     */
    public function getTransmission()
    {
        return $this->transmission;
    }

    /**
     * Set fuel
     *
     * @param string $fuel
     *
     * @return CarAd
     */
    public function setFuel($fuel)
    {
        $this->fuel = $fuel;

        return $this;
    }

    /**
     * Get fuel
     *
     * @return string
     */
    public function getFuel()
    {
        return $this->fuel;
    }

    /**
     * Set enginePower
     *
     * @param integer $enginePower
     *
     * @return CarAd
     */
    public function setEnginePower($enginePower)
    {
        $this->enginePower = $enginePower;

        return $this;
    }

    /**
     * Get enginePower
     *
     * @return int
     */
    public function getEnginePower()
    {
        return $this->enginePower;
    }

    /**
     * Set cubicCapacity
     *
     * @param integer $cubicCapacity
     *
     * @return CarAd
     */
    public function setCubicCapacity($cubicCapacity)
    {
        $this->cubicCapacity = $cubicCapacity;

        return $this;
    }

    /**
     * Get cubicCapacity
     *
     * @return int
     */
    public function getCubicCapacity()
    {
        return $this->cubicCapacity;
    }

    /**
     * Set manufactureYear
     *
     * @param integer $manufactureYear
     *
     * @return CarAd
     */
    public function setManufactureYear($manufactureYear)
    {
        $this->manufactureYear = $manufactureYear;

        return $this;
    }

    /**
     * Get manufactureYear
     *
     * @return int
     */
    public function getManufactureYear()
    {
        return $this->manufactureYear;
    }

    /**
     * Set mileage
     *
     * @param integer $mileage
     *
     * @return CarAd
     */
    public function setMileage($mileage)
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * Get mileage
     *
     * @return int
     */
    public function getMileage()
    {
        return $this->mileage;
    }

    /**
     * Set doors
     *
     * @param string $doors
     *
     * @return CarAd
     */
    public function setDoors($doors)
    {
        $this->doors = $doors;

        return $this;
    }

    /**
     * Get doors
     *
     * @return string
     */
    public function getDoors()
    {
        return $this->doors;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return CarAd
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
     * Set mainImage
     *
     * @param string $mainImage
     *
     * @return CarAd
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * Get mainImage
     *
     * @return string
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CarAd
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return string
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * set dateAdded
     *
     * @param string $dateAdded
     *
     * @return CarAd
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set User
     *
     * @param mixed $user
     *
     * @return CarAd
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}

