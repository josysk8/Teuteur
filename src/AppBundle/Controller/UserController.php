<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
	/**
	 * @Route("/user/follow/{followId}", name="follow_user")
	 * @param integer $followId
	 */
	public function followUserAction($followId)
	{
		$em = $this->getDoctrine()->getManager();
		$userRepository = $this->get('doctrine')
			->getRepository('AppBundle:User');

		$userToFollow = $userRepository->find($followId);
		/** @var User $user */
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		$result = "fail";
		if (!empty($userToFollow) && !empty($user) && $userToFollow->getId() != $user->getId())
		{
			$alreadyFollowed = $user->getFollow();
			$found = false;
			foreach ($alreadyFollowed as $follow)
			{
				if ($follow->getId() == $userToFollow->getId())
				{
					$found = true;
					break;
				}
			}
			if (!$found)
			{
				$user->addFollow($userToFollow);
				$em->persist($user);
				$em->flush();
				$result = "success";
			}
		}

		$data = array('result' => $result);

		$response = new JsonResponse();
		$response->setData($data);
		$response->send();
		die;
	}

	/**
	 * @Route("/user/unfollow/{unfollowId}", name="unfollow_user")
	 * @param integer $unfollowId
	 */
	public function unfollowUserAction($unfollowId)
	{
		$em = $this->getDoctrine()->getManager();
		$userRepository = $this->get('doctrine')
			->getRepository('AppBundle:User');

		$userToUnfollow = $userRepository->find($unfollowId);
		/** @var User $user */
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		$result = "fail";
		if (!empty($userToUnfollow) && !empty($user) && $userToUnfollow->getId() != $user->getId())
		{
			$alreadyFollowed = $user->getFollow();
			$found = false;
			foreach ($alreadyFollowed as $follow)
			{
				if ($follow->getId() == $userToUnfollow->getId())
				{
					$found = true;
					break;
				}
			}
			if ($found)
			{
				$user->removeFollow($userToUnfollow);
				$em->persist($user);
				$em->flush();
				$result = "success";
			}
		}

		$data = array('result' => $result);

		$response = new JsonResponse();
		$response->setData($data);
		$response->send();
		die;
	}
}
