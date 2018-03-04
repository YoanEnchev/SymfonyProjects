<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductInCart
 *
 * @ORM\Table(name="products_in_cart")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductInCartRepository")
 */
class ProductInCart
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
     * @ORM\Column(name="userRequiredQuantity", type="integer")
     */
    private $userRequiredQuantity;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="productInCart")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    public function __construct()
    {
        $this->setUserRequiredQuantity(1);
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
     * Set userRequiredQuantity
     *
     * @param integer $userRequiredQuantity
     *
     * @return ProductInCart
     */
    public function setUserRequiredQuantity($userRequiredQuantity)
    {
        $this->userRequiredQuantity = $userRequiredQuantity;

        return $this;
    }

    /**
     * Get userRequiredQuantity
     *
     * @return int
     */
    public function getUserRequiredQuantity()
    {
        return $this->userRequiredQuantity;
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
     * @return ProductInCart
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }
}

