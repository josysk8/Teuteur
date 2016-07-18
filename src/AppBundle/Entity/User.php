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
     * @ORM\ManyToMany(targetEntity="user",
	 inversedBy="followedby")
	 * @ORM\JoinTable(name="follow_user")
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
}