<?php

namespace IW\EasySurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuizController extends Controller {

    private function getProjects() {

        $em = $this->getDoctrine()->getManager();

        //se obtienen los proyectos de los que el usuario es propietario
        $projects_property = $em->getRepository('IWEasySurveyBundle:Project')->findBy(array('user_id' => $this->get('session')->get('id')));
        $projects_array = array();
        foreach ($projects_property as $data) {
            $projects_array[$data->getId()] = $data->getName();
        }

        //se obtienen los proyectos de los que el usuario es colaborador
        /*
          $projects_collaborate = $em->getRepository('IWEasySurveyBundle:ProjectUser')->findBy(array('userId'=>$this->get('session')->get('id')));
          foreach ($projects_collaborate as $data) {
          $project_aux = $em->getRepository('IWEasySurveyBundle:Project')->find($data->getProjectId());
          $projects_array[$project_aux->getId()]=$project_aux->getName();
          }
         */

        return $projects_array;
    }

    public function createAction(Request $request) {

        $arrayProjects = $this->getProjects();
        $error = '';

        if (empty($arrayProjects)) {
            $error = 'No existen proyectos, antes de crear una encuesta debe usted crear un proyecto';
        }


        $form = $this->createFormBuilder()
                ->add('name', 'text', array('label' => 'Nombre de la nueva Encuesta', 'required' => true))
                ->add('description', 'textarea', array('label' => 'Descripci�n de la Encuesta', 'required' => false))
                ->add('project', 'choice', array('label' => 'Proyecto', 'choices' => $arrayProjects))
                ->add('create', 'submit', array('label' => 'Crear'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $projectData = $form->getData();
            $project = new \IW\EasySurveyBundle\Entity\Quiz;
            $project->setName($projectData['name']);
            $project->setDescription($projectData['description']);
            $project->setProjectId($projectData['project']);
            $project->setUserId($this->get('session')->get('id'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_quiz'));
        }

        return $this->render('IWEasySurveyBundle:Quiz:form.html.twig', array('form' => $form->createView(), 'error' => $error));
    }

    public function manageAction() {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->findby(array('userId' => $this->get('session')->get('id')));
        return $this->render('IWEasySurveyBundle:Quiz:manage.html.twig', array('quiz' => $quiz));
    }

    public function editAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($id);
        $error = '';
        $arrayProjects = $this->getProjects();

        $form = $this->createFormBuilder()
                ->add('name', 'text', array('label' => 'Nombre de la Encuesta', 'data' => $quiz->getName(), 'required' => true))
                ->add('description', 'textarea', array('label' => 'Descripci�n de la Encuesta', 'data' => $quiz->getDescription(), 'required' => false))
                ->add('project', 'choice', array('label' => 'Proyecto', 'choices' => $arrayProjects, 'data' => $quiz->getProjectId()))
                ->add('create', 'submit', array('label' => 'Crear'))
                ->getForm();

        $form->handleRequest($request);

        //se envia el formulario
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $quiz->setName($dataForm['name']);
            $quiz->setDescription($dataForm['description']);
            $quiz->setProjectId($dataForm['project']);
            $quiz->setUserId($this->get('session')->get('id'));
            $em->persist($quiz);
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_quiz'));
        }

        return $this->render('IWEasySurveyBundle:Quiz:form.html.twig', array('form' => $form->createView(), 'error' => $error));
    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($id);
        $em->remove($quiz);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_quiz'));
    }

    public function manageQuestionsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository('IWEasySurveyBundle:Question')->findBy(array('quizId' => $id));
        return $this->render('IWEasySurveyBundle:Quiz:manageQuestions.html.twig', array('id' => $id, 'questions' => $questions));
    }

    private function getTypeQuestions() {

        $questions = array();
        $questions[0] = 'Numérica';
        $questions[1] = 'Texto';
        $questions[2] = 'Elección única';
        $questions[3] = 'Elección múltiple';
        return $questions;
    }
    
    private function getTypeQuestionsDefinitions() {
        $definitions = array();
        
        $definitions[0] = 'El encuestado introducirá un valor num�rico por teclado';
        $definitions[1] = 'El encuestado introducirá una respuesta escrita por teclado';
        $definitions[2] = 'El encuestado elegirá una �nica respuesta entre las presentadas';
        $definitions[3] = 'El encuestado podrá elegir varias respuestas entre las presentdas';
        
        return $definitions;
    }

    public function addQuestionAction($id, Request $request) {

        $questions = $this->getTypeQuestions();
        $definitions = $this->getTypeQuestionsDefinitions();
        $form = $this->createFormBuilder()
                ->add('name', 'text', array('label' => 'Enunciado de la Pregunta'))
                ->add('type', 'choice', array('label' => 'Tipo de Pregunta', 'choices' => $questions))
                ->add('create', 'submit', array('label' => 'Añadir Pregunta'))
                ->getForm();
        $form->handleRequest($request);
        //se envia el formulario
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $question = new \IW\EasySurveyBundle\Entity\Question;
            $question->setName($dataForm['name']);
            $question->setTypeId($dataForm['type']);
            $question->setQuizId($id);
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            
            if($dataForm['type'] < 2) {
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions', array('id' => $id)));
            }
            else
            {
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_question_option', array('id' => $question->getId())));
            }
                
            }

        return $this->render('IWEasySurveyBundle:Quiz:addQuestion.html.twig', array('id' => $id, 'form' => $form->createView()));
    }

    public function editQuestionAction($id, Request $request) {

        $questions = $this->getTypeQuestions();
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        $form = $this->createFormBuilder()
                ->add('name', 'text', array('label' => 'Enunciado de la Pregunta', 'data' => $question->getName()))
                ->add('type', 'choice', array('label' => 'Tipo de Pregunta', 'choices' => $questions, 'data' => $question->getTypeId()))
                ->add('modify', 'submit', array('label' => 'Modificar Pregunta'))
                ->getForm();

        $form->handleRequest($request);

        //se envia el formulario
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $question->setName($dataForm['name']);
            $question->setTypeId($dataForm['type']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions', array('id' => $question->getQuizId())));
        }

        return $this->render('IWEasySurveyBundle:Quiz:addQuestion.html.twig', array('id' => $id, 'form' => $form->createView()));
    }

    public function deleteQuestionAction($id) {

        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        $em->remove($question);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions', array('id' => $question->getQuizId())));
    }
    
    public function manageQuestionOptionAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        $form = $this->createFormBuilder()
                ->add('option1', 'text', array('label' => 'Opción 1: ', 'required' => true))
                ->add('option2', 'text', array('label' => 'Opción 2: ', 'required' => true))
                ->add('option3', 'text', array('label' => 'Opción 3: ', 'required' => false))
                ->add('modify', 'submit', array('label' => 'Guardar opciones'))
                ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $em = $this->getDoctrine()->getManager();
            
            for($i = 1; $i <= 3; $i++){
                if($dataForm['option'.$i] != ""){
                    $option = new \IW\EasySurveyBundle\Entity\TextQuestionOption;
                    $option->setQuestionId($id);
                    $option->setText($dataForm['option'.$i]);
                    $em->persist($option);
                }
            }
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions', array('id' => $question->getQuizId())));
        }
        
        return $this->render('IWEasySurveyBundle:Quiz:manageQuestionOption.html.twig', array('id' => $id, 'question' => $question, 'form' => $form->createView()));
    }
    
    public function editQuestionOptionAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        $options= $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->findBy(array('questionId' => $id ));
        //echo'<pre>';print_r($options[0]->getText()); die();echo'</pre>';
        
        /**
         * Cargando datos actuales en los arrays auxiliares
         */
        $oldText = array();
        $oldId = array();
        for($i = 0; $i < 3; $i++){
            if (isset($options[$i])){
                $oldData[$i] = $options[$i]->getText();
                $oldId[$i] = $options[$i]->getId();
            }else{
                $oldData[$i] = "";
                $oldId[$i] = -1;
            }
        }
        
       
        /**
         * Creando formularios para cada opción
         */
        $form1 = $this->createFormBuilder()
                ->add('option', 'text', array('label' => "Opción 1: ", 'data' => $oldData[0]))
                ->add('id', 'text', array('label' => "ID HIDDEN", 'data' => $oldId[0]))
                ->add('modify', 'submit', array('label' => 'Moodificar Opción'))
                ->getForm();
        
        $form2 = $this->createFormBuilder()
                ->add('option', 'text', array('label' => "Opción 2: ", 'data' => $oldData[1]))
                ->add('id', 'text', array('label' => "ID HIDDEN", 'data' => $oldId[1]))
                ->add('modify', 'submit', array('label' => 'Moodificar Opción'))
                ->getForm();
        
        $form3 = $this->createFormBuilder()
                ->add('option', 'text', array('label' => "Opción 3: ", 'data' => $oldData[2]))
                ->add('id', 'text', array('label' => "ID HIDDEN", 'data' => $oldId[2]))
                ->add('modify', 'submit', array('label' => 'Moodificar Opción'))
                ->getForm();
        
        /**
         * isValids para cada formulario
         */
        
        if($form1->isValid()){
            $dataForm = $form1->getData();
            $em = $this->getDoctrine()->getManager();
            //echo'<pre>';print_r('IS VALID'); die();echo'</pre>';
            
            if($dataForm['id'] != -1){
                //Get object
                $option = getRepository('IWEasySurveyBundle:TextQuestionOption')->find($dataForm['id']);
                //echo'<pre>';print_r($option); die();echo'</pre>';
                if($dataForm['option'] != "") {
                    //Update
                    $option->setText($dataForm['option']);
                    $em->persist($option);
                } else {
                    //Remove
                    //TODO
                    //$em->remove($option);
                }
                
                $em->flush();
            }
            return $this->render('IWEasySurveyBundle:Quiz:editQuestionOption.html.twig', array('id' => $id, 'question' => $question, 
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView()));
        }
        
        
        
        /*
        $form = $this->createFormBuilder()
                ->add('option1', 'text', array('label' => 'Opción 1: ', 'data' => $oldData[0]))
                ->add('option2', 'text', array('label' => 'Opción 2: ', 'data' => $oldData[1]))
                ->add('option3', 'text', array('label' => 'Opción 3: ', 'data' => $oldData[2]))
                ->add('modify', 'submit', array('label' => 'Guardar opciones'))
                ->getForm();
        $form->handleRequest($request);
        
         if ($form->isValid()) {
            $dataForm = $form->getData();
            $em = $this->getDoctrine()->getManager();
            
            for($i = 1; $i <= 3; $i++){
                if($dataForm['option'.$i] != ""){
                    $option = new \IW\EasySurveyBundle\Entity\TextQuestionOption;
                    $option->setQuestionId($id);
                    $option->setText($dataForm['option'.$i]);
                    $em->persist($option);
                }
            }
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions', array('id' => $question->getQuizId())));
        }
         */
         
        
        
        
        return $this->render('IWEasySurveyBundle:Quiz:editQuestionOption.html.twig', array('id' => $id, 'question' => $question, 
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView()));
    }
    
    

}
