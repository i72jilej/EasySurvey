<?php

namespace Ahvega\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users =  $em->getRepository('AhvegaHomeBundle:Users')->findAll();
        return $this->render('AhvegaHomeBundle:Default:index.html.twig', array('users'=> $users));
    }
    
    public function newUserAction()
    {
        return $this->render('AhvegaHomeBundle:Default:addUser.html.twig', array());
    }
    
    public function addUserAction()
    {
        $user = new \Ahvega\HomeBundle\Entity\Users();
        $user->setUsername($_POST['username']);
        $user->setPassword($_POST['password']);
        $user->setFirstname($_POST['firstname']);
        $user->setLastname($_POST['lastname']);
        $user->setEmail($_POST['email']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->forward('AhvegaHomeBundle:Default:index');
    }
    
    public function deleteUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AhvegaHomeBundle:Users')->find($_GET['id']);
        $em->remove($user);
        $em->flush();
        return $this->forward('AhvegaHomeBundle:Default:index');
    }
    
    public function updateUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AhvegaHomeBundle:Users')->find($_GET['id']);        
        return $this->render('AhvegaHomeBundle:Default:updateUser.html.twig', array('user'=>$user));
    }
    
    public function editUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AhvegaHomeBundle:Users')->find($_POST['id']);
        $user->setUsername($_POST['username']);
        $user->setPassword($_POST['password']);
        $user->setFirstname($_POST['firstname']);
        $user->setLastname($_POST['lastname']);
        $user->setEmail($_POST['email']);
        $em->persist($user);
        $em->flush();
        return $this->forward('AhvegaHomeBundle:Default:index');
    }
    
    
}
