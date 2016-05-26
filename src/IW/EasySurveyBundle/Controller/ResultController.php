<?php

namespace IW\EasySurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//use Symfony\Component\HttpFoundation\StreamedResponse;

class ResultController extends Controller
{
     private function getTypeString($idType) 
    {
        switch($idType) {
            case 0: $return = 'Numérica';
                    break;
            case 1: $return = 'Texto';
                    break;
            case 2: $return = 'Elección única';
                    break;
            case 3: $return = 'Elección múltiple';
                    break;
        }        
        return $return;
    }
    
    private function getResults ($id) 
    {
        
        $em = $this->getDoctrine()->getManager();
        
        //se obtienen las respuestas a dicha instancia
        $answers = $em->getRepository('IWEasySurveyBundle:Answers')->findBy(array('idInstance'=>$id));
        
        $values = array ();
        foreach ($answers as $data) {
            $question = $em->getRepository('IWEasySurveyBundle:Question')->find($data->getIdQuestion());
            $body = '';
            if ($data->getIdType()<2) { //pregunta numérica y de texto
                $body = $data->getBody();
            } else {
                $options = explode(',',$data->getBody());
                $body = array ();
                foreach ($options as $option) {
                    $textQuestionOption = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->find($option);
                    if (!empty($textQuestionOption)) {
                        $body[] = array('id'=>$textQuestionOption->getId(),'text'=>$textQuestionOption->getText());
                    }
                }
            }
            $values[] = array ('id'=>$data->getId(),'question'=>$question->getName(),'body'=>$body,'idType'=>$data->getIdType(),'type'=>$this->getTypeString($data->getIdType()));
        }
        return $values;
    }
    
    public function indexAction ($id) 
    {
        $em = $this->getDoctrine()->getManager();
        $instance = $em->getRepository('IWEasySurveyBundle:Instance')->find($id);
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($instance->getQuizId());
        return $this->render('IWEasySurveyBundle:Result:manage.html.twig', array('id'=>$id,'instanceName'=>$instance->getName(),'quizName'=>$quiz->getName()));        
    }
    
    
   public function showDataAction($id) {
       $em = $this->getDoctrine()->getManager();
       $values = $this->getResults($id);
       $instance = $em->getRepository('IWEasySurveyBundle:Instance')->find($id);
       $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($instance->getQuizId());
       return $this->render('IWEasySurveyBundle:Result:result.html.twig', array('id'=>$id,'values'=>$values,'instanceName'=>$instance->getName(),'quizName'=>$quiz->getName()));        
   }


   public function generateCsvAction($id)
    {
        $values = $this->getResults($id);
        $filename = "export_".$id."_".date("Y_m_d_His").".csv";
        $response = $this->render('IWEasySurveyBundle:Result:csv.html.twig', array('id'=>$id,'values' => $values));         
        $response->headers->set('Content-Type', 'text/csv; charset=ISO-8859-2');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);         
        return $response;
    }
    
    
}
