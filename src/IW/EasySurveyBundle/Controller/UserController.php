<?php

namespace IW\EasySurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    
    private function isLogin () {
        if (empty($this->get('session')->get('id'))) {
            return false;
        } 
        return true;
    }
    
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
            ->add('user', 'text', array('label'=>'Usuario','required'=>true))
            ->add('password', 'password', array('label'=>'Contraseña','required'=>true))
            ->add('password2', 'password', array('label'=>'Confirmar Contraseña','required'=>true))
            ->add('name', 'text', array('label'=>'Nombre','required'=>true))    
            ->add('lastName', 'text', array('label'=>'Apellidos','required'=>true))        
            ->add('email', 'email', array('label'=>'Email','required'=>true))    
            ->add('email2', 'email', array('label'=>'Confirmar Email','required'=>true))    
            ->add('create', 'submit', array('label'=>'Crear Usuario'))
            ->getForm();
        
        $form->handleRequest($request);
 
        if ($form->isValid()) {
            
            $userData = $form->getData();
            
            if ($userData['password'] != $userData['password2']) {
                $error = 'Las contraseñas no coinciden';
            }elseif ($userData['email'] != $userData['email2']) {
                $error = 'Los Emails no coinciden';
            }elseif ($this->existUser($userData['user'], $userData['email'])) {
                $error = 'Nombre de usuario o email ya existe en el sistema';
            } else {
                // guardar la tarea en la base de datos
                $user = new \IW\EasySurveyBundle\Entity\User;
                $user->setUsername($userData['user']);
                $user->setPassword(md5($userData['password']));
                $user->setFirstname($userData['name']);
                $user->setLastname($userData['lastName']);
                $user->setEmail($userData['email']);
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
            
            $message = \Swift_Message::newInstance()
                ->setSubject('[EasySurvey] Cuenta confirmada')
                ->setFrom('info@easysurvey.com')
                ->setTo($user[0]->getEmail())
                ->setBody($this->renderView('IWEasySurveyBundle:User:registration_confirm.html.twig',array('user' => $user [0])),'text/html');
            $this->get('mailer')->send($message);
            
            
            
            return $this->render('IWEasySurveyBundle:User:confirmEmail.html.twig', array('message'=>'Usuario confirmado correctamente'));
        }
    }

    public function confirmUserAction()
    {
        return $this->render('IWEasySurveyBundle:User:confirmUser.html.twig', array());
    }
    
    public function modifyUserAction(Request $request)
    {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('IWEasySurveyBundle:User')->find(array('id'=>$this->get('session')->get('id')));
        $error='';
        $form = $this->createFormBuilder()
            ->add('user', 'text', array('label'=>'Usuario', 'required'=>true,'data'=>$user->getUsername()))
            ->add('email', 'email', array('label'=>'Email', 'required'=>true,'data'=>$user->getEmail(),'attr' => array('readonly' => true)))    
            ->add('newEmail', 'email', array('label'=>'Nuevo Email', 'required'=>false))    
            ->add('newEmail2', 'email', array('label'=>'Confirmar Nuevo Email', 'required'=>false))
            ->add('password', 'password', array('label'=>'Contraseña','required'=>true,'data'=>''))
            ->add('password2', 'password', array('label'=>'Confirmar Contraseña', 'required'=>true,'data'=>''))
            ->add('name', 'text', array('label'=>'Nombre', 'required'=>true,'data'=>$user->getFirstname()))    
            ->add('lastName', 'text', array('label'=>'Apellidos', 'required'=>true,'data'=>$user->getLastname()))        
            ->add('save', 'submit', array('label'=>'Guardar Cambios'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $userData = $form->getData();
            
            if ($userData['password'] != $userData['password2']) {
                $error = 'Las contraseñas no coinciden';
            }elseif ($userData ['newEmail'] != $userData ['newEmail2']){
                $error = 'El nuevo Email no coincide';
            }else {
                // modificar el usuario
                $user->setUsername($userData['user']);
                $user->setPassword(md5($userData['password']));
                if ($userData['newEmail'] != NULL) {
                    $user->setEmail($userData['newEmail']);
                }
                $user->setFirstname($userData['name']);
                $user->setLastname($userData['lastName']);
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
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        
        //obtenemos los proyectos del usuario
        $projects = $em->getRepository('IWEasySurveyBundle:Project')->findBy(array ('user_id'=>$this->get('session')->get('id')));
        
        foreach ($projects as $project) {
            
            // Borramos los colaboradores de dicho proyecto
            $collaborator = $em->getRepository('IWEasySurveyBundle:ProjectUser')->findBy(array('projectId'=>$project->getId()));
            foreach ($collaborator as $data) {
                $em->remove($data);
            }

            // Borramos las encuestas asociadas a dicho projecto
            $quizs = $em->getRepository('IWEasySurveyBundle:Quiz')->findBy(array('projectId'=>$project->getId()));
            foreach ( $quizs as $quiz ) {

                //Borramos las preguntas asociadas a la encuesta
                $questions = $em->getRepository('IWEasySurveyBundle:Question')->findBy(array('quizId'=>$quiz->getId()));
                foreach ($questions as $question) {
                    //Borramos las posibles opciones (si las hubiese)
                    $textQuestionOptions = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->findBy(array('questionId'=>$question->getId()));
                    foreach ($textQuestionOptions as $textQuestionOption) {
                        //eliminamos las opciones
                        $em->remove($textQuestionOption);
                    }
                    //eliminamos la pregunta
                    $em->remove($question);
                }

                //buscamos las instancias de la encuesta
                $instances = $em->getRepository('IWEasySurveyBundle:Instance')->findBy(array('quizId'=>$quiz->getId()));
                foreach ($instances as $instance) {
                    //obtenemos la respuesta relacionado con la instancia
                    $answers = $em->getRepository('IWEasySurveyBundle:Answers')->findBy(array('idInstance'=>$instance->getId()));
                    foreach ($answers as $answer) {
                        //eliminamos la respuesta
                        $em->remove($answer);
                    }
                    //eliminamos la instancia
                    $em->remove($instance);
                }
                //eliminamos el cuestionario
                $em->remove($quiz);
            }
        }
        //eliminamos las instancias que el usuario tuviese en otros proyectos
        $instances = $em->getRepository('IWEasySurveyBundle:Instance')->findBy(array('userId'=>$this->get('session')->get('id')));
        foreach ($instances as $instance) {
            $em->remove($instance);
        }
        
        //finalmente borramos el usuario de la bbdd
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
            ->add('user', 'text', array('label'=>'Usuario', 'required'=>true))
            ->add('password', 'password', array('label'=>'Contraseña', 'required'=>true))
            ->add('submit', 'submit', array('label'=>'Entrar'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            //se ha enviado el formulario
            $loginData = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('IWEasySurveyBundle:User')->findby(array('username'=>$loginData['user'],'password'=>md5($loginData['password']),'confirm'=>1));
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
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $session = $request->getSession();
        $session->clear();
        return $this->render('IWEasySurveyBundle:User:logout.html.twig', array());
    }

}
