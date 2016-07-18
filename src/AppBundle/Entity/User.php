<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
	/**
	 * @ORM\OneToMany(targetEntity="Post", mappedBy="author")
	 */
	protected $posts;

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="user",inversedBy="followedby")
	 * @ORM\JoinTable(name="follow_user",
	 *	 joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *	 inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")})
     */
    private $follow;
    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="user", mappedBy="follow")
     */
    private $followedby;

	public function __construct()
	{
		parent::__construct();
		// your own logic
	}

    /**
     * Add post
     *
     * @param \AppBundle\Entity\Post $post
     *
     * @return User
     */
    public function addPost(\AppBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \AppBundle\Entity\Post $post
     */
    public function removePost(\AppBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add follow
     *
     * @param \AppBundle\Entity\user $follow
     *
     * @return User
     */
    public function addFollow(\AppBundle\Entity\user $follow)
    {
        $this->follow[] = $follow;

        return $this;
    }

    /**
     * Remove follow
     *
     * @param \AppBundle\Entity\user $follow
     */
    public function removeFollow(\AppBundle\Entity\user $follow)
    {
        $this->follow->removeElement($follow);
    }

    /**
     * Get follow
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollow()
    {
        return $this->follow;
    }

    /**
     * Add followedby
     *
     * @param \AppBundle\Entity\user $followedby
     *
     * @return User
     */
    public function addFollowedby(\AppBundle\Entity\user $followedby)
    {
        $this->followedby[] = $followedby;

        return $this;
    }

    /**
     * Remove followedby
     *
     * @param \AppBundle\Entity\user $followedby
     */
    public function removeFollowedby(\AppBundle\Entity\user $followedby)
    {
        $this->followedby->removeElement($followedby);
    }

    /**
     * Get followedby
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowedby()
    {
        return $this->followedby;
    }
}
