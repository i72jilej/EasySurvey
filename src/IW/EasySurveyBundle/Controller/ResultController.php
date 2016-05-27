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
        $questions = $this->getIdQuestions($id);
        
        return $this->render('IWEasySurveyBundle:Result:manage.html.twig', array('id'=>$id,'instanceName'=>$instance->getName(),'quizName'=>$quiz->getName()));        
    }
    
    public function showStatisticAction ($id) 
    {
        $questions = $this->getIdQuestions($id);        
        $em = $this->getDoctrine()->getManager();
        $instance = $em->getRepository('IWEasySurveyBundle:Instance')->find($id);
        
        $result_text = array();
        $result_numeric = array();
        $result_simple = array();
        $result_multiple = array();
        foreach ($questions as $data) {
                $answers = $em->getRepository('IWEasySurveyBundle:Answers')->findBy(array('idInstance'=>$id,'idQuestion'=>$data));
                $question = $em->getRepository('IWEasySurveyBundle:Question')->find($data);
                
                switch($question->getTypeId()) {
                            // Respuestas numericas
                    case 0: 
                            $result_numeric[] = array ('questionName'=>$question->getName(),'average'=>$this->getAverage($answers), 'median'=>$this->getMedian($answers),
                                'mode'=>$this->getMode($answers), 'porcentages'=>$this->getPortentages($answers));
                            break;
                            // Respuestas de texto
                    case 1: 
                            $result_text[] = array ('questionName'=>$question->getName(),'responses'=>$this->getTextResponses($answers));
                            break;
                    // Respuestas de eleccion unica
                    case 2:
                            $result_simple[] = array ('questionName'=>$question->getName(), 'porcentages'=>$this->getPortentages($answers));
                            break;
                    // Respuestas de eleccion multiple
                    case 3: 
                            $result_multiple[] = array ('questionName'=>$question->getName(), 'porcentages'=>$this->getPortentages($answers));
                            break;
                }
                
            
            
        }
        
        return $this->render('IWEasySurveyBundle:Result:statistic.html.twig', 
                array('name'=>$instance->getName(),
                        'results_numeric'=>$result_numeric,
                        'results_text'=>$result_text,
                        'results_simple'=>$result_simple,
                        'results_multiple'=>$result_multiple));
    }
    
   public function showDataAction($id) 
    {
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
    
    private function getIdQuestions ($idInstance) 
    {        
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            "SELECT DISTINCT(a.idQuestion)
            FROM IWEasySurveyBundle:Answers a
            WHERE a.idInstance = $idInstance "
        );
        
        $idquestions = $query->getResult();
        $results = array ();
        foreach ($idquestions as $data) {
            $results [] = $data[1];
        }
        
        return $results;
    }
    
    private function getAverage($id)
    {
        $Average = 0;
        foreach ($id as $data) {
            $Average += $data->getBody();
        }
        $Average /= count($id);
        
        return $Average;
    }    
    
    private function getMedian($id)
    {
        foreach ($id as $data){
            $valuesArray[] = $data->getBody();
        }
        sort($valuesArray);
        
        $tam = count($valuesArray);
        if ($tam%2 != 0) {
            $median = $valuesArray[($tam/2)-(1/2)];
        }
        else {
            $median = ($valuesArray[($tam/2)-1] + $valuesArray[($tam/2)])/2;
        }
        
        return $median;
    }
    
    private function getMode($id)
    {
        foreach ($id as $data){
            $valueArray[] = $data->getBody();
        }
        $values = array_count_values($valueArray); 
        $mode = array_search(max($values), $values);
        return $mode;
    }
    
    private function getPortentages($id) //FALTA POR ACABAR
    {
        foreach ($id as $data){
            $valueArray[] = $data->getBody();
        }
        $count = array_count_values($valueArray); 
        $total = array_sum($count);
        
        foreach ($count as &$data)
        {
            $data /= $total;
        }

        return $count;
    }
    
    private function getTextResponses($id)
    {
        foreach ($id as $data){
            $text[] = $data->getBody();
        }
        return $text;
    }
}


