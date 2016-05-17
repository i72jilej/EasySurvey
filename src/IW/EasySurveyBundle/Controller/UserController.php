<?php

namespace IW\EasySurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IW\EasySurveyBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    
    private function existUser ($username, $email) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('IWEasySurveyBundle:User')->findby(array('username'=>$username,'email'=>$email));
        if (empty($user)){
            return false;
        } else {
            return true;
        }
    }

    public function createUserAction(Request $request)
    {
        $error = '';
        $form = $this->createFormBuilder()
            ->add('Usuario', 'text', array('required'=>true))
            ->add('Password', 'password', array('required'=>true))
            ->add('Password2', 'password', array('required'=>true))
            ->add('Nombre', 'text', array('required'=>true))    
            ->add('Apellidos', 'text', array('required'=>true))        
            ->add('Email', 'email', array('required'=>true))    
            ->add('Guardar', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
 
        if ($form->isValid()) {
            
            $userData = $form->getData();
            
            if ($userData['Password'] != $userData['Password2']) {
                $error = 'Las contraseñas no coinciden';
            }elseif ($this->existUser($userData['Usuario'], $userData['Email'])) {
                $error = 'Nombre de usuario o email ya existe en el sistema';
            } else {
                // guardar la tarea en la base de datos
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
        }
        
        return $this->render('IWEasySurveyBundle:User:createUser.html.twig', array('form' => $form->createView(), 'error'=> $error));
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
    
    public function modifyUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('IWEasySurveyBundle:User')->find(array('id'=>$this->get('session')->get('id')));
        $error='';
        $form = $this->createFormBuilder()
            ->add('Usuario', 'text', array('required'=>true,'data'=>$user->getUsername(),'attr' => array('readonly' => true)))
            ->add('Email', 'email', array('required'=>true,'data'=>$user->getEmail(),'attr' => array('readonly' => true)))    
            ->add('Password', 'password', array('required'=>true,'data'=>''))
            ->add('Password2', 'password', array('required'=>true,'data'=>''))
            ->add('Nombre', 'text', array('required'=>true,'data'=>$user->getFirstname()))    
            ->add('Apellidos', 'text', array('required'=>true,'data'=>$user->getLastname()))        
            ->add('Modificar', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $userData = $form->getData();
            
            if ($userData['Password'] != $userData['Password2']) {
                $error = 'Las contraseñas no coinciden';
            } else {
                // modificar el usuario
                $user->setPassword(md5($userData['Password']));
                $user->setFirstname($userData['Nombre']);
                $user->setLastname($userData['Apellidos']);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('iw_easy_survey_homepage'));
            }
        }
        
        return $this->render('IWEasySurveyBundle:User:modifyUser.html.twig', array('form'=>$form->createView(),'error'=>$error));
    }

    public function deleteUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('IWEasySurveyBundle:User')->find(array('id'=>$this->get('session')->get('id')));
        $em->remove($user);
        $em->flush();
        $session = $request->getSession();
        $session->clear();
        return $this->redirect($this->generateUrl('iw_easy_survey_homepage'));
    }

    public function loginAction(Request $request)
    {
        $success = $error = '';
        
        $form = $this->createFormBuilder()
            ->add('Usuario', 'text', array('required'=>true))
            ->add('Password', 'password', array('required'=>true))
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
                return $this->redirect($this->generateUrl('iw_easy_survey_homepage'));
            }
        }
        
        return $this->render('IWEasySurveyBundle:User:login.html.twig', array('form' => $form->createView(),'error'=>$error,'success'=> $success));
    }

    public function logoutAction(Request $request)
    {
        $session = $request->getSession();
        $session->clear();
        return $this->render('IWEasySurveyBundle:User:logout.html.twig', array());
    }

}
