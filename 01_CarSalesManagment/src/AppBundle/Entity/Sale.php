<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sales
 *
 * @ORM\Table(name="sales")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SalesRepository")
 */
class Sale
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
     * @ORM\Column(name="discount", type="float")
     */
    private $discount;

    /**
     *@var string
     *
     * @ORM\Column(name="price_no_discount", type="decimal", precision=10, scale=2)
     */
    private $priceNoDiscount;

    /**
     *@var string
     *
     * @ORM\Column(name="price_discount", type="decimal", precision=10, scale=2)
     */
    private $priceDiscount;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Car")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     */
    private $car;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="sales")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;


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
     * Set discount
     *
     * @param float $discount
     *
     * @return Sale
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Get priceNoDiscount
     *
     * @return string
     */
    public function getPriceNoDiscount()
    {
        return $this->priceNoDiscount;
    }

    /**
     * Set priceNoDiscount
     *
     * @param string $priceNoDiscount
     *
     * @return string
     */
    public function setPriceNoDiscount($priceNoDiscount)
    {
        $this->priceNoDiscount = $priceNoDiscount;

        return $this;
    }

    /**
     * Get pricePriceDiscount
     *
     * @return string
     */
    public function getPriceDiscount()
    {
        if($this->priceDiscount == null) {
            $this->setPriceDiscount();
        }

        return $this->priceDiscount;
    }

    /**
     * Set priceDiscount
     */
    public function setPriceDiscount()
    {
        $priceNoDiscount = $this->priceNoDiscount;
        $this->priceDiscount = $priceNoDiscount - $priceNoDiscount * $this->discount / 100;
    }
}

