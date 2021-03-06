<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="image_address", type="text")
     */
    private $imageAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="date")
     */
    private $dateAdded;

    /**
     * @var string
     *
     * @ORM\Column(name="habitat", type="string", length=255)
     */
    private $habitat;

    /**
     * One Article has Many Tags.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Tag", mappedBy="article", cascade={"persist"}))
     */
    private $tags;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="text")
     */
    private $slug;

    /**
     * One Article has Many comments.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="article", cascade={"persist", "remove"})
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set ImageAddress
     *
     * @param string $imageAddress
     *
     * @return Article
     */
    public function setImageAddress($imageAddress)
    {
        $this->imageAddress = $imageAddress;

        return $this;
    }

    /**
     * Get ImageAddress
     *
     * @return string
     */
    public function getImageAddress()
    {
        return $this->imageAddress;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Article
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param string $habitat
     *
     * @return Article
     */
    public function setHabitat($habitat)
    {
        $this->habitat = $habitat;

        return $this;
    }

    /**
     * @return string
     */
    public function getHabitat()
    {
        return $this->habitat;
    }

    /**
     * Set slug
     *
     * @return Article
     */
    public function setSlug()
    {
        $this->slug = substr ( $this->content , 0, 400) . "...";

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get comments
     *
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set Comments
     *
     * @param ArrayCollection $comments
     *
     * @return Article
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param ArrayCollection $tags
     *
     * @return Article
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
    }

    public function removeCommentsAndTags()
    {
        $this->comments->clear();
        $this->tags->clear();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function checkIfUserCommented(User $user)
    {
        /** @var Comment $comment */
        foreach ($this->comments as $comment) {
            if ($comment->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return float
     */
    public function calcAverageGrade()
    {
        $sum = 0;
        $count = $this->comments->count();

        if($count == 0) {
            return 0;
        }

        /** @var Comment $comment */
        foreach ($this->comments as $comment) {
            $sum += $comment->getGradeNumber();
        }

        return $sum / $count;
    }

    /**
     * @param Tag $tagToCheck
     * @return bool
     */
    public function containsTag(Tag $tagToCheck)
    {
        foreach ($this->getTags() as $tag) {
            if($tagToCheck->getName() == $tag->getName()) {
                return true;
            }
        }

        return false;
    }
}

