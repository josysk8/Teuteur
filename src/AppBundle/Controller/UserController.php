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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
	/**
	 * @Route("/user/follow/{followId}", name="follow_user")
	 * @Security("has_role('ROLE_USER')")
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
	 * @Security("has_role('ROLE_USER')")
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
	 * @Security("has_role('ROLE_USER')")
     */
	public function userEditAction(Request $request)
	{
        // create a user
		/*$user = new User();

		$form = $this->createFormBuilder($user)
		->add('username', TextType::class)
		->add('email', TextType::class)
		->add('save', SubmitType::class, array('label' => 'Create Task'))
		->getForm();*/

		/** @var User $user */
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		$user->setProfilPic("http://www.adweek.com/socialtimes/files/2012/03/twitter-egg-icon.jpg");
		return $this->render('user/edit.html.twig', array(
			'user' => $user,
			));
	}

	/**
	 * @Route("/user/edit/valid", name="user_edit_valid")
	 * @param Request $request
	 */
	public function userEditValidAction(Request $request)
	{
		/** @var User $user */
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		$user->setEmail($request->request->get('email'));
		$user->setUsername($request->request->get('username'));
		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();
		$url = $this->generateUrl('userEdit');
		return $this->redirect($url);
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
	 * @Security("has_role('ROLE_USER')")
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
