<?php

namespace IW\EasySurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name='hola')
    {
        return $this->render('IWEasySurveyBundle:Default:index.html.twig', array('name' => $name));
    }
}
