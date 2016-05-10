<?php

namespace IW\EasySurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IW\EasySurveyBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function createUserAction(Request $request)
    {
        
        $form = $this->createFormBuilder()
            ->add('Usuario', 'text')
            ->add('Password', 'password')
            ->add('Nombre', 'text')    
            ->add('Apellidos', 'text')        
            ->add('Email', 'email')    
            ->add('Guardar', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
 
        if ($form->isValid()) {
            // guardar la tarea en la base de datos
            $userData = $form->getData();
            $user = new \IW\EasySurveyBundle\Entity\User;
            $user->setUsername($userData['Usuario']);
            $user->setPassword(md5($userData['Password']));
            $user->setFirstname($userData['Nombre']);
            $user->setLastname($userData['Apellidos']);
            $user->setEmail($userData['Email']);
            $user->setConfirm(0);
            $seeskeyConfirm = substr(md5(rand()),0,5);
            $user->setSesskeyConfirm($seeskeyConfirm);
            
            $message = \Swift_Message::newInstance()
                ->setSubject('[EasySurvey] Email de confirmación de la creación de la cuenta')
                ->setFrom('info@easysurvey.com')
                ->setTo($user->getEmail())
                ->setBody($this->renderView('IWEasySurveyBundle:User:registration.html.twig',array('seeskey' => $seeskeyConfirm,'username'=>$user->getUsername())),'text/html');
            $this->get('mailer')->send($message);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();            
            return $this->redirect($this->generateUrl('iw_easy_survey_confirm_user'));
        }
        
        return $this->render('IWEasySurveyBundle:User:createUser.html.twig', array('form' => $form->createView(),));
    }
    
    public function confirmEmailUserAction($seeskey, $username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('IWEasySurveyBundle:User')->findby(array('username'=>$username,'sesskeyConfirm'=>$seeskey));
        if (empty($user)) {
            return $this->render('IWEasySurveyBundle:User:confirmEmail.html.twig', array('message'=>'Fallo al confirmar el usuario'));
        } else {
            $user[0]->setConfirm(1);
            $em->persist($user[0]);
            $em->flush();
            return $this->render('IWEasySurveyBundle:User:confirmEmail.html.twig', array('message'=>'Usuario confirmado correctamente'));
        }
    }

    public function confirmUserAction()
    {
        return $this->render('IWEasySurveyBundle:User:confirmUser.html.twig', array());
    }
    
    public function modifyUserAction()
    {
        return $this->render('IWEasySurveyBundle:User:modifyUser.html.twig', array());
    }

    public function deleteUserAction()
    {
        return $this->render('IWEasySurveyBundle:User:deleteUser.html.twig', array());
    }

    public function loginAction(Request $request)
    {
        $success = $error = '';
        
        $form = $this->createFormBuilder()
            ->add('Usuario', 'text')
            ->add('Password', 'password')
            ->add('Acceder', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            //se ha enviado el formulario
            $loginData = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('IWEasySurveyBundle:User')->findby(array('username'=>$loginData['Usuario'],'password'=>md5($loginData['Password']),'confirm'=>1));
            if (empty($user)) {
                $error = 'Error en el nombre de usuario y/o contraseña';
                $success = '';
            } else {
                $error = '';
                $success = 'Login correcto';
                $session = $request->getSession();
                $session->set('username', $user[0]->getUsername());
                $session->set('firstname', $user[0]->getFirstname());
                $session->set('lastname', $user[0]->getLastname());
                $session->set('email', $user[0]->getEmail());
                $session->set('id', $user[0]->getId());
            }
        }
        
        return $this->render('IWEasySurveyBundle:User:login.html.twig', array('form' => $form->createView(),'error'=>$error,'success'=> $success));
    }

    public function logoutAction()
    {
        return $this->render('IWEasySurveyBundle:User:logout.html.twig', array());
    }

}
