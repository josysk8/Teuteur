<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use AppBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function homeAction(Request $request)
	{
		/** @var PostRepository $postRepository */
		$postRepository = $this->get('doctrine')
			->getRepository('AppBundle:Post');

		$authorsList = array(1);
		$posts = $postRepository->getLastForHome($authorsList, 0, 50);

		return $this->render('posts/posts.html.twig', [
			'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
			'posts' => $posts
		]);
	}

	/**
	 * @Route("/post/create", name="create_post")
	 * @param Request $request
	 */
	public function createAction(Request $request)
	{
		$postRepository = $this->getDoctrine()->getRepository('AppBundle:Post');
		$em = $this->getDoctrine()->getManager();

		$result = "fail";
		/** @var User $user */
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		//TODO DEBUG
		//$user = $this->getDoctrine()->getRepository('AppBundle:User')->find(2);
		$post = null;
		if (!empty($user))
		{
			$post = new Post();
			$post->setMessage($request->request->get('message'));
			$post->setStatus(1);
			$post->setAuthor($user);
			if (!empty($request->request->get('parent')))
				$parent = $postRepository->find($request->request->get('parent'));
			else
				$parent = null;
			$post->setParent($parent);
			if (!empty($request->request->get('repost_target')))
				$repostTarget = $postRepository->find($request->request->get('repost_target'));
			else
				$repostTarget = null;
			$post->setRepostTarget($repostTarget);
			$post->setRepost($request->request->get('repost'));
			$post->setDateUpdate(null);
			$post->setDateCreate(new \DateTime());

			$em->persist($post);
			$em->flush();
			$result = "success";
		}
		$data = array('result' => $result, 'data' => $post);
		$data = json_encode($data);
		$response = new JsonResponse();
		$response->setData($data);
		$response->send();
		die;
	}

	/**
	 * @Route("/post/edit", name="edit_post")
	 * @param Request $request
	 */
	public function editAction(Request $request)
	{
		$postRepository = $this->getDoctrine()->getRepository('AppBundle:Post');
		$em = $this->getDoctrine()->getManager();

		$result = "fail";
		/** @var User $user */
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		//TODO DEBUG
		//$user = $this->getDoctrine()->getRepository('AppBundle:User')->find(2);
		$post = null;
		if (!empty($user))
		{
			$post = $postRepository->find($request->request->get('id'));
			if (!empty($post))
			{
				$post->setMessage($request->request->get('message'));
				$post->setDateUpdate(new \DateTime());

				$em->persist($post);
				$em->flush();
				$result = "success";
			}
		}
		$data = array('result' => $result, 'data' => $post);
		$data = json_encode($data);
		$response = new JsonResponse();
		$response->setData($data);
		$response->send();
		die;
	}

	/**
	 * @Route("/post/delete", name="delete_post")
	 * @param Request $request
	 */
	public function deleteAction(Request $request)
	{
		$postRepository = $this->getDoctrine()->getRepository('AppBundle:Post');
		$em = $this->getDoctrine()->getManager();

		$result = "fail";
		$post = $postRepository->find($request->request->get('id'));
		if (!empty($post))
		{
			$post->setStatus(0);
			$post->setDateUpdate(new \DateTime());

			$em->persist($post);
			$em->flush();
			$result = "success";
		}
		$data = array('result' => $result);
		$data = json_encode($data);
		$response = new JsonResponse();
		$response->setData($data);
		$response->send();
		die;
	}

	public function getReportedPostsAction()
	{
		$postRepository = $this->getDoctrine()->getRepository('AppBundle:Post');


	}
}
