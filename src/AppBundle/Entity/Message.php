<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="message", type="string", length=1024)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="sender", referencedColumnName="id")
	 */
	private $sender;

	/**
	 * @var Channel
	 *
	 * @ORM\ManyToOne(targetEntity="Channel", inversedBy="messages")
	 * @ORM\JoinColumn(name="channel", referencedColumnName="id")
	 */
	private $channel;

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
     * @return Message
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

	/**
	 * @return User
	 */
	public function getSender()
	{
		return $this->sender;
	}

	/**
	 * @param User $sender
	 * @return Message
	 */
	public function setSender($sender)
	{
		$this->sender = $sender;
		return $this;
	}

	/**
	 * @return Channel
	 */
	public function getChannel()
	{
		return $this->channel;
	}

	/**
	 * @param Channel $channel
	 * @return Message
	 */
	public function setChannel($channel)
	{
		$this->channel = $channel;
		return $this;
	}


}
