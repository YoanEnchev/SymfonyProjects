<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

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
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * One User has Many CarAds.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CarAd", mappedBy="user")
     */
    private $carAds;

    /**
     * Many Users have Many Ads in checkLaterList.
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\CarAd")
     * @ORM\JoinTable(name="users_ads_check_later",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="ad_id", referencedColumnName="id")}
     *      )
     */
    private $checkLaterAds;

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

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->carAds = new ArrayCollection();
        $this->checkLaterAds = new ArrayCollection();
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
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
     * @return User
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
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
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

    /**
     * @param Role $role
     *
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;
        return $this;
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
        // TODO: Implement getSalt() method.
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
     * Get carAds
     *
     * @return mixed
     */
    public function getCarAds()
    {
        return $this->carAds;
    }

    /**
     * Set carAds
     *
     * @param mixed $carAds
     *
     * @return User
     */
    public function setCarAds($carAds)
    {
        $this->carAds = $carAds;

        return $this;
    }

    /**
     * @return bool
     */
    public function usernameRegistered($userList)
    {
        /** @var User $registeredUser */
        foreach ($userList as $registeredUser) {
            if ($registeredUser->getUsername() === $this->getUsername() && $registeredUser->getId() !== $this->getId()) {
                return true;
            }
        }
        return false;
    }
    /**
     * @return bool
     */
    public function emailRegistered($userList)
    {
        /** @var User $registeredUser */
        foreach ($userList as $registeredUser) {
            if ($registeredUser->getEmail() === $this->getEmail() && $registeredUser->getId() !== $this->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return ArrayCollection
     */
    public function getCheckLaterAds()
    {
        return $this->checkLaterAds;
    }

    /**
     * @param ArrayCollection $checkLaterAds
     *
     * @return User
     */
    public function setCheckLaterAds($checkLaterAds)
    {
        $this->checkLaterAds = $checkLaterAds;

        return $this;
    }

    /**
     * @param CarAd $carAd
     */
    public function addToCheckLaterList($carAd)
    {
        $this->checkLaterAds[] = $carAd;
    }

    /**
     * @param CarAd $carAd
     */
    public function removeFromCheckLaterList($carAd)
    {
        $this->checkLaterAds->removeElement($carAd);
    }
}

