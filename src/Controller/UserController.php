<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    
    public function login(AuthenticationUtils  $autenticationUtils){
        $error=$autenticationUtils->getLastAuthenticationError();
        $lastUsername=$autenticationUtils->getLastUsername();
        return $this->render('user/login.html.twig',[
           'error'=>$error,
            'last_username'=>$lastUsername
        ]);
    }
    
    
    
}
