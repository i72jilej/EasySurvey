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
        $projects_collaborate = $em->getRepository('IWEasySurveyBundle:ProjectUser')->findBy(array('userId'=>$this->get('session')->get('id')));
        foreach ($projects_collaborate as $data) {
            $project_aux = $em->getRepository('IWEasySurveyBundle:Project')->find($data->getProjectId());
            $projects_array[$project_aux->getId()]=$project_aux->getName();
        }
        
        return $projects_array;
    }
    
    public function createAction(Request $request)
    {
        
        $arrayProjects = $this->getProjects();
        $error = '';

        if (empty($arrayProjects)) {
            $error = 'No existen proyectos, antes de crear un cuestionario debe usted crear un proyecto';
        }
                
        
        $form = $this->createFormBuilder()
            ->add('Nombre', 'text', array('required'=>true))
            ->add('Descripcion', 'textarea', array('required'=>true))
            ->add('Proyecto','choice',array('choices'=>$arrayProjects))
            ->add('Crear', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $projectData = $form->getData();
            $project = new \IW\EasySurveyBundle\Entity\Quiz;
            $project->setName($projectData['Nombre']);
            $project->setDescription($projectData['Descripcion']);
            $project->setProjectId($projectData['Proyecto']);
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
            ->add('Nombre', 'text', array('required'=>true,'data'=>$quiz->getName()))
            ->add('Descripcion', 'textarea', array('required'=>true, 'data'=>$quiz->getDescription()))
            ->add('Proyecto','choice',array('choices'=>$arrayProjects,'data'=>$quiz->getProjectId()))
            ->add('Crear', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        //se envia el formulario
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $quiz->setName($dataForm['Nombre']);
            $quiz->setDescription($dataForm['Descripcion']);
            $quiz->setProjectId($dataForm['Proyecto']);
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
        return $this->render('IWEasySurveyBundle:Quiz:manageQuestions.html.twig', array('id'=>$id));
    }
    
    private function getTypeQuestions (){
        
        $questions = array();
        $questions[0] = 'Grado';
        return $questions;
    }
    
    public function addQuestionAction($id, Request $request) {
        
        $questions = $this->getTypeQuestions();
        
        $form = $this->createFormBuilder()
            ->add('Nombre', 'text', array())
            ->add('Tipo','choice',array('choices'=>$questions))
            ->add('Crear', 'submit')
            ->getForm();
        
        /*
        $form->handleRequest($request);
        
        //se envia el formulario
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $quiz->setName($dataForm['Nombre']);
            $quiz->setDescription($dataForm['Descripcion']);
            $quiz->setProjectId($dataForm['Proyecto']);
            $quiz->setUserId($this->get('session')->get('id'));
            $em->persist($quiz);
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_quiz'));
        }
        */
        return $this->render('IWEasySurveyBundle:Quiz:addQuestion.html.twig', array('id'=>$id,'form'=>$form->createView()));
    }
}
