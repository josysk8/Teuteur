<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
	/**
     * @Route("/user/edit", name="userEdit")
     */
	public function userEditAction(Request $request)
	{
        // create a user
		//$user = new User();
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		/*
		$user->find(1);
			*/

		$form = $this->createFormBuilder($user)
		->add('username', TextType::class)
		->add('email', TextType::class)
		->add('password', TextType::class)
		->add('profilPic', TextType::class)
		->add('save', SubmitType::class, array('label' => 'Create Task'))
		->getForm();

		return $this->render('user/edit.html.twig', array(
			'form' => $form->createView(),
			'user' => $user,
			));
	}

	/**
	 * @Route("/user/{id}/addadmin", name="user_add_admin")
	 */
	public function addAdminRoleAction($id)
	{
		$userRepository = $this->get('doctrine')
			->getRepository('AppBundle:User');
		/** @var User $user */
		$user = $userRepository->find($id);
		$user->addRole("ROLE_ADMIN");
		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();
		$data = array('result' => 'success');
		$data = json_encode($data);
		$response = new JsonResponse();
		$response->setData($data);
		$response->send();
		die;
	}

	/**
	 * @Route("/user/{id}/nonfriend", name="users_non_friend")
	 */
	public function getNonFriend($id)
	{
		/** @var UserRepository $userRepository */
		$userRepository = $this->get('doctrine')
			->getRepository('AppBundle:User');
		/** @var User $user */
		$user = $userRepository->find($id);
		$usersList = $userRepository->getNonFriend($user);
		return $this->render('user/nonfriend.html.twig', array(
			'users' => $usersList,
		));
	}
}
