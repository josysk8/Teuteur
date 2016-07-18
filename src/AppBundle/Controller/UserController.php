<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends Controller
{
	/**
     * @Route("/user/edit", name="userEdit")
     */
	public function userEditAction(Request $request)
	{
        // create a user
		$user = new User();
		/*
		$user->find(1);
			*/

		$form = $this->createFormBuilder($user)
		->add('username', TextType::class)
		->add('email', TextType::class)
		->add('save', SubmitType::class, array('label' => 'Create Task'))
		->getForm();

		return $this->render('user/edit.html.twig', array(
			'form' => $form->createView(),
			'user' => array("nom" => "Smith", "prenom" => "John", "profilPic" => "http://www.adweek.com/socialtimes/files/2012/03/twitter-egg-icon.jpg"),
			));
	}
}
