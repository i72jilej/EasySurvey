<?php    
namespace IWEasySurveyBundle\Twig;

use Symfony\Component\HttpFoundation\Session\Session;

class AppExtension extends \Twig_Extension {

    public function getGlobals() {
        $session = new Session();
        return array(
            'session' => $session->all(),
        );
    }

    public function getName() {
        return 'app_extension';
    }
}