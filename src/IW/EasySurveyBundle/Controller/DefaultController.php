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
}
