<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post
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
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreate", type="datetime")
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdate", type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="user", inversedBy="posts")
	 * @ORM\JoinColumn(name="author", referencedColumnName="id")
     */
    private $author;

    /**
     * @var bool
     *
     * @ORM\Column(name="repost", type="boolean")
     */
    private $repost;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="post")
	 * @ORM\JoinColumn(name="repost_target", referencedColumnName="id", nullable=true)
     */
    private $repostTarget;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="post", inversedBy="responses")
	 * @ORM\JoinColumn(name="parent", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="user")
	 * @ORM\JoinTable(name="postlike_user")
     */
    private $likes;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="user")
	 *  @ORM\JoinTable(name="postreport_user")
     */
    private $report;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

	/**
	 * @var array
	 *
	 * @ORM\OneToMany(targetEntity="Post", mappedBy="parent")
	 */
	private $responses;

	public function __construct()
	{
		$this->likes = new ArrayCollection();
		$this->report = new ArrayCollection();
		$this->responses = new ArrayCollection();
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
     * Set message
     *
     * @param string $message
     *
     * @return Post
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Post
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return Post
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set author
     *
     * @param \stdClass $author
     *
     * @return Post
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \stdClass
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set repost
     *
     * @param boolean $repost
     *
     * @return Post
     */
    public function setRepost($repost)
    {
        $this->repost = $repost;

        return $this;
    }

    /**
     * Get repost
     *
     * @return bool
     */
    public function getRepost()
    {
        return $this->repost;
    }

    /**
     * Set repostTarget
     *
     * @param \stdClass $repostTarget
     *
     * @return Post
     */
    public function setRepostTarget($repostTarget)
    {
        $this->repostTarget = $repostTarget;

        return $this;
    }

    /**
     * Get repostTarget
     *
     * @return \stdClass
     */
    public function getRepostTarget()
    {
        return $this->repostTarget;
    }

    /**
     * Set parent
     *
     * @param \stdClass $parent
     *
     * @return Post
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \stdClass
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set likes
     *
     * @param array $likes
     *
     * @return Post
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get likes
     *
     * @return array
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set report
     *
     * @param array $report
     *
     * @return Post
     */
    public function setReport($report)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * Get report
     *
     * @return array
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

	/**
	 * @return array
	 */
	public function getResponses()
	{
		return $this->responses;
	}

	/**
	 * @param array $responses
	 * @return Post
	 */
	public function setResponses($responses)
	{
		$this->responses = $responses;
		return $this;
	}

	/**
	 * Add report
	 *
	 * @param \AppBundle\Entity\Post $post
	 *
	 * @return User
	 */
	public function addReport(\AppBundle\Entity\User $user)
	{
		$this->report[] = $user;

		return $this;
	}

	/**
	 * Remove report
	 *
	 * @param \AppBundle\Entity\User $user
	 */
	public function removeReport(\AppBundle\Entity\Post $user)
	{
		$this->report->removeElement($user);
	}

	/**
	 * Add like
	 *
	 * @param \AppBundle\Entity\User $user
	 *
	 * @return User
	 */
	public function addLike(\AppBundle\Entity\User $user)
	{
		$this->likes[] = $user;

		return $this;
	}

	/**
	 * Remove like
	 *
	 * @param \AppBundle\Entity\User $user
	 */
	public function removeLike(\AppBundle\Entity\Post $user)
	{
		$this->likes->removeElement($user);
	}
}

