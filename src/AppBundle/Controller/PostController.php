<?php

namespace AppBundle\Controller;

use AppBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
		$posts = $postRepository->getLastForHome($authorsList, 0, 20);
		$postRepository->findBy(array('author'=>$objevt, 'parent'=>$parent))

		dump($posts);die;
	}
}
