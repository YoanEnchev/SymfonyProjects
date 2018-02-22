<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="original_price", type="decimal", precision=10, scale=2)
     */
    private $originalPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="discount", type="integer")
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(name="promotion_price", type="decimal", precision=10, scale=2)
     */
    private $promotionPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="image_address", type="text")
     */
    private $imageAddress;

    /**
     * @var \datetime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * One Product has Many Reviews.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Review", mappedBy="product")
     */
    private $reviews;

    public function __construct(string $make, string $model, float $originalPrice, string $imageAddress, int $discount)
    {
        $this->setMake($make);
        $this->setModel($model);
        $this->setOriginalPrice($originalPrice);
        $this->setImageAddress($imageAddress);
        $this->setDiscount($discount);
        $this->setPromotionPrice($originalPrice, $discount);
        $this->setDateAdded(new \DateTime("now"));
        $this->reviews = new ArrayCollection();
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
     * @return Product
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
     * @return Product
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
     * Set originalPrice
     *
     * @param string $originalPrice
     *
     * @return Product
     */
    public function setOriginalPrice($originalPrice)
    {
        $this->originalPrice = $originalPrice;

        return $this;
    }

    /**
     * Get originalPrice
     *
     * @return string
     */
    public function getOriginalPrice()
    {
        return $this->originalPrice;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     *
     * @return Product
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set promotionPrice
     *
     * @param float $originalPrice
     * @param int $discount
     * @return Product
     * @internal param string $promotionPrice
     *
     */
    public function setPromotionPrice(float $originalPrice, int $discount)
    {
        $this->promotionPrice = $originalPrice - ($discount / 100) * $originalPrice;

        return $this;
    }

    /**
     * Get promotionPrice
     *
     * @return string
     */
    public function getPromotionPrice()
    {
        return $this->promotionPrice;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Product
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageAddress(): string
    {
        return $this->imageAddress;
    }

    /**
     * @param string $imageAddress
     *
     * @return Product
     */
    public function setImageAddress(string $imageAddress)
    {
        $this->imageAddress = $imageAddress;

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
     * @return Product
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param $reviews
     * @return Product
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * @param Review $review
     */
    public function addReview(Review $review)
    {
        $this->reviews[] = $review;
    }

    /**
     * @return float|int
     */
    public function countOfReviews()
    {
        return $count = sizeof($this->reviews);
    }

    /**
     * @return float|int
     */
    public function averageGrade()
    {
        $sum = 0;

        if( $this->countOfReviews() == 0)
        {
            return 0;
        }

        foreach ($this->reviews as $review)
        {
            $sum += $review->getGrade();
        }

        return $sum / $this->countOfReviews();
    }
}

