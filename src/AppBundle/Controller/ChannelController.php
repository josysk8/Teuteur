<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Channel;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Repository\ChannelRepository;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ChannelController extends Controller
{
	/**
	 * @Route("/channel/user/{idUser}", name="get_channels_user")
	 * @param $user
	 */
	public function getChannelsByUserAction($idUser)
	{
		/** @var ChannelRepository $channelRepository */
		$channelRepository = $this->getDoctrine()->getRepository('AppBundle:Channel');
		$channels = $channelRepository->getChannelsByUser($idUser);
		return $this->render('messages/channels.html.twig', [
			'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
			'channels' => $channels
		]);
	}

	/**
	 * @Route("/channel/{id}/messages", name="get_messages")
	 */
	public function getMessagesAction($id)
	{
		/** @var ChannelRepository $channelRepository */
		$channelRepository = $this->getDoctrine()->getRepository('AppBundle:Channel');
		/** @var Channel $channel */
		$channel = $channelRepository->find($id);
		$messages = $channel->getMessages();
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		return $this->render('messages/chat.html.twig', [
			'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
			'messages' => $messages,
			'channel' => $channel,
			'urlretour' => '/channel/user/'.$user->getId()
		]);
	}

	/**
	 * @Route("/channel/{id}/messages/create", name="create_message")
	 */
	public function createMessageAction(Request $request, $id)
	{
		/** @var ChannelRepository $channelRepository */
		$channelRepository = $this->getDoctrine()->getRepository('AppBundle:Channel');
		/** @var Channel $channel */
		$channel = $channelRepository->find($id);

		/** @var UserRepository $userRepository */
		$userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
		/** @var User $user */
		//$user = $userRepository->find($request->request->get('userid'));
		$user = $this->container->get('security.token_storage')->getToken()->getUser();

		//$user = $userRepository->find(2);
		$message = new Message();
		$message->setMessage($request->request->get('message'));
		$message->setDate(new \DateTime());
		$message->setChannel($channel);
		$message->setSender($user);
		$channel->addMessage($message);

		$em = $this->getDoctrine()->getManager();
		$em->persist($channel);
		$em->flush();

		//$this->redirectToRoute('get_messages', array('id'=>$id));
		$url = $this->generateUrl('get_messages', array('id' => $id));
		return $this->redirect($url);
	}

	/**
	 * @Route("/channel/create", name="create_channel")
	 * @param Request $request
	 */
	public function createChannelAction(Request $request)
	{
		/** @var ChannelRepository $channelRepository */
		$channelRepository = $this->getDoctrine()->getRepository('AppBundle:Channel');
		$channel = new Channel();
		$channel->setName($request->request->get('name'));
		/** @var UserRepository $userRepository */
		$userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
		foreach ($request->request->get('users') as $item)
		{
			$user = $userRepository->find($item);
			$channel->addUser($user);
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($channel);
		$em->flush();
	}

	/**
	 * @Route("/channel/{id}/adduser/{iduser}")
	 * @param Request $request
	 */
	public function addUserAction($id, $iduser)
	{
		/** @var ChannelRepository $channelRepository */
		$channelRepository = $this->getDoctrine()->getRepository('AppBundle:Channel');
		$channel = $channelRepository->find($id);
		/** @var UserRepository $userRepository */
		$userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
		$user = $userRepository->find($iduser);
		$found = false;
		foreach ($channel->getUsers() as $item)
		{
			if ($item->getId() == $user->getId())
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$channel->addUser($user);
			$em = $this->getDoctrine()->getManager();
			$em->persist($channel);
			$em->flush();
		}
	}
}
