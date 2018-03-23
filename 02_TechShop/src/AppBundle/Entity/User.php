<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, Serializable
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role")
     * @ORM\JoinTable(name="users_roles",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *     )
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ProductInCart")
     * @ORM\JoinTable(name="users_products_in_cart",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_in_cart_id", referencedColumnName="id", unique=false)}
     *      )
     */
    private $productsInShoppingCart;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinTable(name="users_products_wishlist",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", unique=false)}
     *      )
     */
    private $productsInWishlist;

    /**
     * One User has Many Reviews.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Review", mappedBy="user")
     */
    private $reviews;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->productsInShoppingCart = new ArrayCollection();
        $this->productsInWishlist = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param ProductInCart $product
     * @return User
     */
    public function addToShoppingCart(ProductInCart $product)
    {
        if (!$this->productsInShoppingCart->contains($product)) {
            $this->productsInShoppingCart[] = $product;
        }

        return $this;
    }

    public function removeProdFromCart(ProductInCart $productToRemove)
    {
        $this->productsInShoppingCart->removeElement($productToRemove);
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function addToWishlist(Product $product)
    {
        if (!$this->productsInWishlist->contains($product)) {
            $this->productsInWishlist[] = $product;
        }

        return $this;
    }

    public function removeProdFromWishlist(Product $productToRemove)
    {
        $this->productsInWishlist->removeElement($productToRemove);
    }

    /**
     * Returns the Role granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the Role might be stored on a ``Role`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user Role
     */
    public function getRoles()
    {
        $stringRoles = [];
        foreach ($this->roles as $role) {
            /** @var $role Role */
            $stringRoles[] = $role->getRole();
        }
        return $stringRoles;
    }

    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    public function clearShoppingCart() //remove out of stock products
    {
        /** @var ProductInCart $prod */
        foreach ($this->productsInShoppingCart as $prod) {
            if ($prod->getProduct()->getQuantity() <= 0) {
                $this->productsInShoppingCart->removeElement($prod);
            }
        }
    }

    public function clearWishlist() //remove out of stock products
    {
        /** @var Product $prod */
        foreach ($this->getProductsInWishlist() as $prod) {
            if ($prod->getQuantity() <= 0) {
                $this->productsInWishlist->removeElement($prod);
            }
        }
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->getId(),
            $this->getUsername(),
            $this->getPassword()
        ]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized);
    }

    /**
     * @return ArrayCollection
     */
    public function getProductsInShoppingCart()
    {
        return $this->productsInShoppingCart;
    }

    /**
     * @param mixed $productsInShoppingCart
     *
     * @return User
     */
    public function setProductsInShoppingCart($productsInShoppingCart)
    {
        $this->productsInShoppingCart = $productsInShoppingCart;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductsInWishlist()
    {
        return $this->productsInWishlist;
    }

    /**
     * @param mixed $productsInWishlist
     */
    public function setProductsInWishlist($productsInWishlist)
    {
        $this->productsInWishlist = $productsInWishlist;
    }

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param mixed $reviews
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    public function totalCostOfShoppingCart(): float
    {
        $totalCost = 0;

        /** @var ProductInCart $prodInCart */
        foreach ($this->productsInShoppingCart as $prodInCart) {
            $totalCost += $prodInCart->getUserRequiredQuantity() * $prodInCart->getProduct()->getPromotionPrice();
        }

        return $totalCost;
    }

    public function allProdsFromCartAvaliable(): bool
    {
        $prodsInCart = $this->getProductsInShoppingCart();
        /** @var ProductInCart $prodInCart */
        foreach ($prodsInCart as $prodInCart) {
            if ($prodInCart->getUserRequiredQuantity() > $prodInCart->getProduct()->getQuantity()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function usernameRegistered($userList)
    {

        foreach ($userList as $registeredUser) {
            if ($registeredUser->getUsername() === $this->getUsername() && $registeredUser->getId() !== $this->getId()) {
                return true;
            }
        }
        return false;
    }
}

