<?php

namespace IW\EasySurveyBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    
    private function isLogin () {
        if (empty($this->get('session')->get('id'))) {
            return false;
        } 
        return true;
    }
    
    private function existProjectName ($name, $id) {
        $em = $this->getDoctrine()->getManager();
        $projects_aux = $em->getRepository('IWEasySurveyBundle:Project')->findBy(array('name'=>$name));
        
        if ($id==-1) { //si es -1 es un insert por tanto buscamos en la bbdd cualquiera
            
            if (count($projects_aux)>0){
                return 1;
            } else {
                return 0;
            }
        } else { //al tener id es un update por lo tanto por tanto lo descartamos a el mismo
            $return = 0;
            foreach ($projects_aux as $data) {
                if ($data->getId()!=$id) { //no es el mismo
                    $return = 1;
                }
            }
            return $return;
        }
        
    }
    
    public function createAction(Request $request)
    {
        
        $error = '';
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $form = $this->createFormBuilder()
            ->add('name', 'text', array('label'=>'Nombre del nuevo Proyecto','required'=>true))
            ->add('description', 'textarea', array('label'=>'Descripción del Proyecto','required'=>false))
            ->add('create', 'submit', array('label'=>'Crear'))
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $user_id = $this->get('session')->get('id');
            $projectData = $form->getData();
            if ($this->existProjectName($projectData['name'],-1)){
                $error = 'Ya existe un proyecto en el sistema con ese nombre';
            } else {    
                $project = new \IW\EasySurveyBundle\Entity\Project;
                $project->setName($projectData['name']);
                $project->setDescription($projectData['description']);
                $project->setUserId($user_id);
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $em->flush();    
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_project'));
            }
        }
        
        return $this->render('IWEasySurveyBundle:Project:form.html.twig', array('form' => $form->createView(),'error'=>$error));
    }
    
    private function getCollaborateProject() 
    {
        $em = $this->getDoctrine()->getManager();
        $projectsUsers = $em->getRepository('IWEasySurveyBundle:ProjectUser')->findby(array('userId'=>$this->get('session')->get('id')));
        $projects = array ();
        
        foreach ($projectsUsers as $data) {
            $projects[] = $em->getRepository('IWEasySurveyBundle:Project')->find($data->getProjectId());
        }
         
        return $projects;
    }


    public function manageAction() {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('IWEasySurveyBundle:Project')->findby(array('user_id'=>$this->get('session')->get('id')));
        $collaborateProject = $this->getCollaborateProject();
        return $this->render('IWEasySurveyBundle:Project:manage.html.twig', array('ownerProjects'=>$projects,'collaborateProjects'=>$collaborateProject));
    }
    
    public function deleteAction ($id) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($id);
        if ($project->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        // Borramos los colaboradores de dicho proyecto
        $collaborator = $em->getRepository('IWEasySurveyBundle:ProjectUser')->findBy(array('projectId'=>$id));
        foreach ($collaborator as $data) {
            $em->remove($data);
        }
        
        // Borramos las encuestas asociadas a dicho projecto
        $quizs = $em->getRepository('IWEasySurveyBundle:Quiz')->findBy(array('projectId'=>$id));
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
        
        //por ultimo borramos el proyecto
        $em->remove($project);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_project'));
    }
    
    
    private function getAllUser () {
        $em = $this->getDoctrine()->getManager();
        $arrayUser = array ();
        $alluser = $em->getRepository('IWEasySurveyBundle:User')->findAll();
        foreach ($alluser as $data) {
            if ($data->getId() != $this->get('session')->get('id')) {
                $arrayUser[$data->getId()] = $data->getFirstname().' '.$data->getLastname().' ('.$data->getUsername().')';
            }
        }        
        return $arrayUser;
    }
    
    private function getUserProjectSelected($project_id) {
        $em = $this->getDoctrine()->getManager();
        $user_selected = array();
        $usersSelecteds = $em->getRepository('IWEasySurveyBundle:ProjectUser')->findBy(array('projectId'=>$project_id));
        foreach ($usersSelecteds as $data) {
            $user_selected[]=$data->getUserId();
        }
        return $user_selected;
    }

    public function editAction ($id, Request $request) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($id);
        if ($project->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        $error = '';
        
        $form = $this->createFormBuilder()
            ->add('name', 'text', array('label'=>'Nombre del Proyecto','required'=>true, 'data'=>$project->getName(), 'required'=>true))
            ->add('description', 'textarea', array('label'=>'Descripción del Proyecto', 'data'=>$project->getDescription(),'required'=>false))
            ->add('modify', 'submit',array('label'=>'Modificar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $projectData = $form->getData();
            if ($this->existProjectName($projectData['name'],$project->getId())) {
                $error = 'Ya existe un proyecto en el sistema con ese nombre';
            } else {
                $project->setName($projectData['name']);
                $project->setDescription($projectData['description']);
                $em->persist($project);
                $em->flush();    
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_project'));
            }
        }
        
        return $this->render('IWEasySurveyBundle:Project:form.html.twig', array('form' => $form->createView(),'error'=>$error));
    }
    
    public function collaboratorAction ($id, Request $request) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($id);
        if ($project->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        $arrayUser = $this->getAllUser();
        $user_selecteds = $this->getUserProjectSelected($id);
        
        $form = $this->createFormBuilder()
            ->add('subscriber','choice',array('label'=>'Subscriptores','choices'=>$arrayUser,'data'=>$user_selecteds,'multiple'=>true))
            ->add('modify', 'submit',array('label'=>'Modificar',))
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $collaboratortData = $form->getData();
            
            //se eliminan los colaboradores existentes
            $projectUser = $em->getRepository('IWEasySurveyBundle:ProjectUser')->findBy(array('projectId'=>$id));
            foreach ($projectUser as $data) {
                $em->remove($data);
                $em->flush();
            }
            
            //se crean los nuevo colaboradores
            foreach ($collaboratortData['subscriber'] as $data) {
                $projectUser = new \IW\EasySurveyBundle\Entity\ProjectUser;
                $projectUser->setProjectId($id);
                $projectUser->setUserId($data);
                $em->persist($projectUser);
                $em->flush();   
            }
            
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_project'));
        }
        
        return $this->render('IWEasySurveyBundle:Project:collaborator.html.twig', array('form' => $form->createView(),));
    }
}
