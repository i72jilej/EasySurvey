<?php

namespace IW\EasySurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('IWEasySurveyBundle:Default:index.html.twig', array());
    }
    
    public function contactAction()
    {
        return $this->render('IWEasySurveyBundle:Default:contact.html.twig', array());
    }
    
    public function errorLoginAction () {
        return $this->render('IWEasySurveyBundle:Default:errorlogin.html.twig', array());
    }
    
    public function errorAction () {
        return $this->render('IWEasySurveyBundle:Default:error.html.twig', array());
    }
}
