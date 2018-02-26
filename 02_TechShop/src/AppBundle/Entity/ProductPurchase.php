<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductPurchase
 *
 * @ORM\Table(name="product_purchases")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductPurchaseRepository")
 */
class ProductPurchase
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
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="creditCardNumber", type="string", length=255)
     */
    private $creditCardNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cardValidThrough", type="date")
     */
    private $cardValidThrough;

    /**
     * @var string
     */
    private $inputDateValidThrough;

    /**
     * One productPurchase has One product.
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;


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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return ProductPurchase
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return ProductPurchase
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return ProductPurchase
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return ProductPurchase
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set creditCardNumber
     *
     * @param string $creditCardNumber
     *
     * @return ProductPurchase
     */
    public function setCreditCardNumber($creditCardNumber)
    {
        $this->creditCardNumber = $creditCardNumber;

        return $this;
    }

    /**
     * Get creditCardNumber
     *
     * @return string
     */
    public function getCreditCardNumber()
    {
        return $this->creditCardNumber;
    }

    /**
     * Set cardValidThrough
     *
     * @return ProductPurchase
     */
    public function setCardValidThrough()
    {
        if($this->validThroughFormatIsValid()) {

            $monthAndYear = explode('/', $this->inputDateValidThrough);
            $month = $monthAndYear[0];
            $year = $monthAndYear[1];

            $date = new \DateTime();
            $date->setDate($year, $month, 1);
            $lastDay = $date->format('t');

            $validThroughDate = new \DateTime();
            $validThroughDate->setDate($year, $month, $lastDay);
            $this->cardValidThrough = $validThroughDate;
        }
        return $this;
    }

    /**
     * Get cardValidThrough
     *
     * @return \DateTime
     */
    public function getCardValidThrough()
    {
        return $this->cardValidThrough;
    }

    public function cardHasExpired(): bool
    {
        if ($this->cardValidThrough < new \DateTime()) {
            return true;
        }

        return false;
    }

    public function cardNumberIsValid(): bool
    {
        if (preg_match("/^\d{4} \d{4} \d{4} \d{4}$/", $this->creditCardNumber)) {
            return true;
        }

        return false;
    }

    public function validThroughFormatIsValid(): bool
    {
        if (preg_match("/^(\d{1,2})\/(\d{4})$/", $this->inputDateValidThrough)) {
            return true;
        }

        return false;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return ProductPurchase
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return string
     */
    public function getInputDateValidThrough(): ?string
    {
        return $this->inputDateValidThrough;
    }

    /**
     * @param string $inputDateValidThrough
     * @return ProductPurchase
     */
    public function setInputDateValidThrough(string $inputDateValidThrough)
    {
        $this->inputDateValidThrough = $inputDateValidThrough;
        $this->setCardValidThrough();

        return $this;
    }

    public function validMonth(): bool
    {
        $monthAndYear = explode('/', $this->inputDateValidThrough);
        $month = $monthAndYear[0];

        if($month < 1 || $month > 12) {
            return false;
        }

        return true;
    }
}

