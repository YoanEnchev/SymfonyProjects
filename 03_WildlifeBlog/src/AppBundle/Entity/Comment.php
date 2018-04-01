<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\Column(name="grade_number", type="integer")
     */
    private $gradeNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="grade_words", type="string", length=255)
     */
    private $gradeWords;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * Many Comments have One Article.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Article", inversedBy="comments")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;

    /**
     * Many comments have One User.
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


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
     * Set gradeNumber
     *
     * @param integer $gradeNumber
     *
     * @return Comment
     */
    public function setGradeNumber($gradeNumber)
    {
        $this->gradeNumber = $gradeNumber;
        $this->setGradeWords();

        return $this;
    }

    /**
     * Get gradeNumber
     *
     * @return int
     */
    public function getGradeNumber()
    {
        return $this->gradeNumber;
    }

    /**
     * Set gradeWords
     */
    public function setGradeWords()
    {
        switch ($this->gradeNumber)
        {
            case 1:
                $this->gradeWords = 'Bad';
                break;
            case 2:
                $this->gradeWords ='Unsatisfying';
                break;
            case 3:
                $this->gradeWords ='Average';
                break;
            case 4:
                $this->gradeWords = 'Good';
                break;
            case 5:
                $this->gradeWords = 'Excellent';
                break;
        }
    }

    /**
     * Get gradeWords
     *
     * @return string
     */
    public function getGradeWords()
    {
        return $this->gradeWords;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
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
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     *
     * @return Comment
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Comment
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}

