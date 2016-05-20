<?php

namespace IW\EasySurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuizController extends Controller
{
    
    private function getProjects() {
        
        $em = $this->getDoctrine()->getManager();
        
        //se obtienen los proyectos de los que el usuario es propietario
        $projects_property = $em->getRepository('IWEasySurveyBundle:Project')->findBy(array('user_id'=>$this->get('session')->get('id')));
        $projects_array = array();
        foreach ($projects_property as $data) {
            $projects_array[$data->getId()]=$data->getName();
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
    
    public function createAction(Request $request)
    {
        
        $arrayProjects = $this->getProjects();
        $error = '';

        if (empty($arrayProjects)) {
            $error = 'No existen proyectos, antes de crear una encuesta debe usted crear un proyecto';
        }
                
        
        $form = $this->createFormBuilder()
            ->add('name', 'text', array('label'=>'Nombre de la nueva Encuesta','required'=>true))
            ->add('description', 'textarea', array('label'=>'Descripción de la Encuesta', 'required'=>false))
            ->add('project','choice',array('label'=>'Proyecto','choices'=>$arrayProjects))
            ->add('create', 'submit',array('label'=>'Crear'))
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
        
        return $this->render('IWEasySurveyBundle:Quiz:form.html.twig', array('form' => $form->createView(),'error'=>$error));
    }
    
    public function manageAction() {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->findby(array('userId'=>$this->get('session')->get('id')));
        return $this->render('IWEasySurveyBundle:Quiz:manage.html.twig', array('quiz'=>$quiz));
    }
    
    public function editAction ($id, Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($id);
        $error = '';
        $arrayProjects = $this->getProjects();
        
        $form = $this->createFormBuilder()
            ->add('name', 'text', array('label'=>'Nombre de la Encuesta','data'=>$quiz->getName(), 'required'=>true))
            ->add('description', 'textarea', array('label'=>'Descripción de la Encuesta', 'data'=>$quiz->getDescription(),'required'=>false))
            ->add('project','choice',array('label'=>'Proyecto','choices'=>$arrayProjects,'data'=>$quiz->getProjectId()))
            ->add('create', 'submit',array('label'=>'Crear'))
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
        
        return $this->render('IWEasySurveyBundle:Quiz:form.html.twig', array('form' => $form->createView(),'error'=>$error));
        
    }
    
    public function deleteAction ($id) {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($id);
        $em->remove($quiz);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_quiz'));
    }
    
    public function manageQuestionsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository('IWEasySurveyBundle:Question')->findBy(array('quizId'=>$id));
        return $this->render('IWEasySurveyBundle:Quiz:manageQuestions.html.twig', array('id'=>$id,'questions'=>$questions));
    }
    
    private function getTypeQuestions (){
        
        $questions = array();
        $questions[0] = 'Grado';
        return $questions;
    }
    
    public function addQuestionAction($id, Request $request) {
        
        $questions = $this->getTypeQuestions();
        $form = $this->createFormBuilder()
            ->add('name', 'text', array('label'=>'Enunciado de la Pregunta'))
            ->add('type','choice',array('label'=>'Tipo de Pregunta', 'choices'=>$questions))
            ->add('create', 'submit', array('label'=>'Añadir Pregunta'))
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
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions', array('id' => $id)));
        }
        
        return $this->render('IWEasySurveyBundle:Quiz:addQuestion.html.twig', array('id'=>$id,'form'=>$form->createView()));
    }
    
    public function editQuestionAction($id, Request $request) {
        
        $questions = $this->getTypeQuestions();
        $em = $this->getDoctrine()->getManager(); 
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        $form = $this->createFormBuilder()
            ->add('name', 'text', array('label'=>'Enunciado de la Pregunta','data'=>$question->getName()))
            ->add('type','choice',array('label'=>'Tipo de Pregunta','choices'=>$questions,'data'=>$question->getTypeId()))
            ->add('modify', 'submit', array('label'=>'Modificar Pregunta'))
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
        
        return $this->render('IWEasySurveyBundle:Quiz:addQuestion.html.twig', array('id'=>$id,'form'=>$form->createView()));
    }
    
    public function deleteQuestionAction($id) {
        
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        $em->remove($question);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions',array('id'=>$question->getQuizId())));
    }
}
