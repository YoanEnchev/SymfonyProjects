<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
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
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * One User has Many Comments.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="user")
     */
    private $comments;

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
     * Many Users have Many Articles in readLaterList.
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Article")
     * @ORM\JoinTable(name="users_articles_read_later",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id")}
     *      )
     */
    private $readLaterList;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->roles = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection $comments
     *
     * @return User
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

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

    /**
     * @return ArrayCollection
     */
    public function getReadLaterList()
    {
        return $this->readLaterList;
    }

    /**
     * @param ArrayCollection $readLaterList
     * @return User
     */
    public function setReadLaterList(ArrayCollection $readLaterList)
    {
        $this->readLaterList = $readLaterList;

        return $this;
    }

    /**
     * @param Article $article
     */
    public function addToReadLaterList(Article $article)
    {
        $this->readLaterList[] = $article;
    }

    /**
     * @param Article $article
     * @return bool
     */
    public function checkIfArticleAddedInLaterList(Article $article)
    {
        foreach ($this->readLaterList as $articleInList) {
            if ($articleInList === $article) {
                return true;
            }
        }

        return false;
    }

    public function removeFromReadLaterList(Article $article)
    {
        /** @var ArrayCollection $articleInList */
        $articlesInList = $this->readLaterList;

        foreach ($articlesInList as $articleInList) {
            if ($articleInList === $article) {
                $articlesInList->removeElement($articleInList);
            }
        }
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

    /**
     * @return bool
     */
    public function emailRegistered($userList)
    {

        foreach ($userList as $registeredUser) {
            if ($registeredUser->getEmail() === $this->getEmail() && $registeredUser->getId() !== $this->getId()) {
                return true;
            }
        }
        return false;

    }
}

