<?php

namespace IW\EasySurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuizController extends Controller {

    
    private function isLogin () {
        if (empty($this->get('session')->get('id'))) {
            return false;
        } 
        return true;
    }
    
    private function getProjects() {

        $em = $this->getDoctrine()->getManager();
        //se obtienen los proyectos de los que el usuario es propietario
        $projects_property = $em->getRepository('IWEasySurveyBundle:Project')->findBy(array('user_id' => $this->get('session')->get('id')));
        $projects_array = array();
        foreach ($projects_property as $data) {
            $projects_array[$data->getId()] = $data->getName();
        }
        return $projects_array; 
    }
    
    public function createAction(Request $request) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $arrayProjects = $this->getProjects();
        $error = '';

        if (empty($arrayProjects)) {
            $error = 'No existen proyectos, antes de crear una encuesta debe usted crear un proyecto';
        }

        $form = $this->createFormBuilder()
                ->add('name', 'text', array('label' => 'Nombre de la nueva Encuesta', 'required' => true))
                ->add('description', 'textarea', array('label' => 'Descripción de la Encuesta', 'required' => false))
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_quiz',array('id'=>-1)));
        }

        return $this->render('IWEasySurveyBundle:Quiz:form.html.twig', array('form' => $form->createView(), 'error' => $error));
    }
    
    private function getUserProject ($projectId) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($projectId);
        $user = $em->getRepository('IWEasySurveyBundle:User')->find($project->getUserId());
        return $user;
    }
    
    private function quizIsInstance ( $quizId ) {
        
        $em = $this->getDoctrine()->getManager();
        $quizs = $em->getRepository('IWEasySurveyBundle:Instance')->findby(array('quizId' => $quizId));
        if (count($quizs)>0) {
            return 1;
        } else {
            return 0;
        }
        
    }
    
    public function manageAction($id) 
    {        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        //se obtienen los proyectos del usuario en session
        $projects = $this->getProjects();
        $results = array ();
        $project_results = array ();
        $project_results[] = array ('id'=>-1, 'name'=>'- Todos -');
        
        foreach ($projects as $key => $project) {
            $project_results[] = array ('id'=>$key, 'name'=>$project);
            $user = $this->getUserProject($key);
            //se obtienen los cuestionarios del proyecto en cuestión
            $quizs = $em->getRepository('IWEasySurveyBundle:Quiz')->findBy(array('projectId' => $key));
            foreach ($quizs as $quiz) {
                $isInstance = $this->quizIsInstance($quiz->getId());
                if ($id != -1 ) {
                    if ($id == $key) {
                        if ($isInstance) {
                            $results[] = array ('id'=>$quiz->getId(), 'name'=>$quiz->getName(), 'project'=> $project,'edit'=>0,'username'=>$user->getUsername());
                        } else {
                            $results[] = array ('id'=>$quiz->getId(), 'name'=>$quiz->getName(), 'project'=> $project,'edit'=>1,'username'=>$user->getUsername());
                        }
                    }
                } else {
                    if ($isInstance) {
                        $results[] = array ('id'=>$quiz->getId(), 'name'=>$quiz->getName(), 'project'=> $project,'edit'=>0,'username'=>$user->getUsername());
                    } else {
                        $results[] = array ('id'=>$quiz->getId(), 'name'=>$quiz->getName(), 'project'=> $project,'edit'=>1,'username'=>$user->getUsername());
                    }
                }
                
            }
        }
        
        //obtenemos los proyectos en las que el usuario es colaborador
        $helpers  = $em->getRepository('IWEasySurveyBundle:ProjectUser')->findby(array('userId' => $this->get('session')->get('id')));
        foreach ($helpers as $helper) {
            $projects = $em->getRepository('IWEasySurveyBundle:Project')->findBy(array('id' => $helper->getProjectId()));
            foreach ($projects as $project) {
                $user = $this->getUserProject($project->getId());
                $project_results[] = array ('id' => $project->getId(), 'name'=>$project->getName());
                //se obtienen los cuestionarios del proyecto en cuestión
                $quizs = $em->getRepository('IWEasySurveyBundle:Quiz')->findby(array('projectId' => $project->getId()));
                foreach ($quizs as $quiz) {                    
                    if ($id != -1 ) {
                        if ($id == $project->getId()) {
                            $results[] = array ('id'=>$quiz->getId(), 'name'=>$quiz->getName(), 'project'=> $project->getName(),'edit'=>0,'username'=>$user->getUsername());
                        }
                    } else {
                        $results[] = array ('id'=>$quiz->getId(), 'name'=>$quiz->getName(), 'project'=> $project->getName(),'edit'=>0,'username'=>$user->getUsername());
                    }
                    
                    
                }
            }
        }
                
        return $this->render('IWEasySurveyBundle:Quiz:manage.html.twig', array('quiz' => $results, 'projects'=>$project_results, 'actual_project'=>$id));
    }

    public function editAction($id, Request $request) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($id);
        
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($quiz->getProjectId());
        if ($project->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }        
        
        $error = '';
        $arrayProjects = $this->getProjects();

        $form = $this->createFormBuilder()
                ->add('name', 'text', array('label' => 'Nombre de la Encuesta', 'data' => $quiz->getName(), 'required' => true))
                ->add('description', 'textarea', array('label' => 'Descripción de la Encuesta', 'data' => $quiz->getDescription(), 'required' => false))
                ->add('project', 'choice', array('label' => 'Proyecto', 'choices' => $arrayProjects, 'data' => $quiz->getProjectId()))
                ->add('create', 'submit', array('label' => 'Guardar'))
                ->getForm();

        $form->handleRequest($request);

        //se envia el formulario
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $quiz->setName($dataForm['name']);
            $quiz->setDescription($dataForm['description']);
            $quiz->setProjectId($dataForm['project']);
            $em->persist($quiz);
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_quiz',array('id'=>-1)));
        }
        return $this->render('IWEasySurveyBundle:Quiz:form.html.twig', array('form' => $form->createView(), 'error' => $error));
    }

    public function manageQuestionsAction($id) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository('IWEasySurveyBundle:Question')->findBy(array('quizId' => $id));
        $options = $this->getTypeQuestions();
        return $this->render('IWEasySurveyBundle:Quiz:manageQuestions.html.twig', array('id' => $id, 'questions' => $questions, 'options'=>$options));
    }

    private function getTypeQuestions() {
        $questions = array();
        $questions[0] = 'Numérica';
        $questions[1] = 'Texto';
        $questions[2] = 'Elección única';
        $questions[3] = 'Elección múltiple';
        return $questions;
    }
    
    
    public function addQuestionAction($id, Request $request) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $questions = $this->getTypeQuestions();
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
            } else{
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_question_option', array('id' => $question->getId())));
            }  
        }

        return $this->render('IWEasySurveyBundle:Quiz:addQuestion.html.twig', array('id' => $id, 'form' => $form->createView()));
    }
    
    public function deleteAction($id) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($id);
        
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($quiz->getProjectId());
        if ($project->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        //al eliminar el cuestionario hay que eliminar las preguntas de dicho cuestionario y todas las opciones si estas existen
        //obtenemos las preguntas de dicho cuestionario
        $questions = $em->getRepository('IWEasySurveyBundle:Question')->findBy(array('quizId' => $id));
        foreach ($questions as $question) {
            //se comprueba si es de opcion simple o multiple para eliminar las opciones
            if ($question->getTypeId()>=2) {
                $options = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->findBy(array('questionId' => $question->getId() ));
                foreach ($options as $option) {
                    $em->remove($option);
                }
            }
            $em->remove($question);
        }
        $em->remove($quiz);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_quiz',array('id'=>-1)));
    }
    
    public function editQuestionAction($id, Request $request) {

        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $questions = $this->getTypeQuestions();
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        
        $quiz= $em->getRepository('IWEasySurveyBundle:Quiz')->find($question->getQuizId());
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($quiz->getProjectId());
        if ($project->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        $form = $this->createFormBuilder()
                ->add('name', 'text', array('label' => 'Enunciado de la Pregunta', 'data' => $question->getName()))
                ->add('type', 'choice', array('label' => 'Tipo de Pregunta', 'choices' => $questions, 'data' => $question->getTypeId()))
                ->add('modify', 'submit', array('label' => 'Modificar Pregunta'))
                ->getForm();

        $form->handleRequest($request);

        //se envia el formulario
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $change = 0;
            //se comprueba si se ha cambiado la pregunta por tipo opción a texto 
            //o numérica para borrar las opciones en dicho caso
            if ($question->getTypeId()>1 && $dataForm['type']<2) {
                $change = 1;
                $options = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->findBy(array('questionId' => $id ));
                foreach ($options as $data) {
                    $em->remove($data);
                }
                $em->flush();
            }
            
            $question->setName($dataForm['name']);
            $question->setTypeId($dataForm['type']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            if ( $dataForm['type'] < 2 ) {
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions', array('id' => $question->getQuizId())));
            } else {
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_question_option', array('id' => $question->getId())));
            }
        }

        return $this->render('IWEasySurveyBundle:Quiz:addQuestion.html.twig', array('id' => $id, 'form' => $form->createView()));
    }

    public function deleteQuestionAction($id) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        
        $quiz= $em->getRepository('IWEasySurveyBundle:Quiz')->find($question->getQuizId());
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($quiz->getProjectId());
        if ($project->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        //si el tipo es de opcion simple o multiple hay que eliminar también las posibles opciones (tanto simples como multiples)
        if ($question->getTypeId()>1) {
            $options = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->findBy(array('questionId' => $id ));
            foreach ($options as $data) {
                $em->remove($data);
            }
        }
        $em->remove($question);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions', array('id' => $question->getQuizId())));
    }
    
    public function manageQuestionOptionAction($id, Request $request){
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        $options = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->findBy(array('questionId' => $id ));
        
        return $this->render('IWEasySurveyBundle:Quiz:editQuestionOption.html.twig', array('id' => $id, 'options' => $options,'question'=>$question, 'quiz_id'=>$question->getQuizID()));
    }
    
    public function addOptionAction($id, Request $request) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($question->getQuizId());
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($quiz->getProjectId());
        if ($project->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        $form = $this->createFormBuilder()
                ->add('option', 'text', array('label' => 'Opción: ', 'required' => true))
                ->add('add', 'submit', array('label' => 'Añadir'))
                ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $option = new \IW\EasySurveyBundle\Entity\TextQuestionOption;
            $option->setQuestionId($id);
            $option->setText($dataForm['option']);
            $em->persist($option);
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_question_option', array('id' => $option->getQuestionId())));
        }
        
        return $this->render('IWEasySurveyBundle:Quiz:manageQuestionOption.html.twig', array('id' => $id, 'question' => $question, 'form' => $form->createView()));
    }
    
    public function editOptionAction ($id, Request $request) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $option = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->find($id);
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($option->getQuestionId());
        
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($question->getQuizId());
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($quiz->getProjectId());
        if ($project->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        $form = $this->createFormBuilder()
                ->add('option', 'text', array('label' => 'Opción: ', 'required' => true,'data'=>$option->getText()))
                ->add('add', 'submit', array('label' => 'Modificar'))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $dataForm = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $option->setText($dataForm['option']);
            $em->persist($option);
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_question_option', array('id' => $option->getQuestionId())));
        }
        
        return $this->render('IWEasySurveyBundle:Quiz:manageQuestionOption.html.twig', array('id' => $id,'question'=>$question, 'form' => $form->createView()));
    }
    
    public function deleteOptionAction ($id) {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $option = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->find($id);
        
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($option->getQuestionId());
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($question->getQuizId());
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($quiz->getProjectId());
        if ($project->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        $em->remove($option);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_question_option', array('id' => $option->getQuestionId())));        
    }
    
    public function generateInstanceAction($id, Request $request) 
    {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $access = 0;
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($id);
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($quiz->getProjectId());
        if ($project->getUserId()==$this->get('session')->get('id')) { //comprobamos si es el propietario del proyecto de la encuesta
            $access = 1;
        } else {//comprobamos si colaborador
            $projectUsers = $em->getRepository('IWEasySurveyBundle:ProjectUser')->findBy(array('projectId'=>$project->getId()));
            foreach ($projectUsers as $projectUser) {
                if ( $projectUser->getUserId() == $this->get('session')->get('id') ) {
                    $access = 1;
                }
            }
        }
        
        if ($access==0) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        $form = $this->createFormBuilder()
                ->add('name', 'text', array(
                    'label' => 'Nombre de la instancia: ', 
                    'required' => true,
                    'data' => ''))
                
                ->add('finishdate', 'date', array(
                    'label' => 'Fecha de finalización de la encuesta: ', 
                    'required' => true,
                    'pattern' => '{{ day }}-{{ month }}-{{ year }}',
                    'data' => new \DateTime("now"),
                    'years' => range(date ("Y"), 2050)))
                
                ->add('add', 'submit', array('label' => 'Enviar'))
                ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $dataForm = $form->getData();    
            $instance = new \IW\EasySurveyBundle\Entity\Instance;
            $instance->setQuizId($id);
            $instance->setTimecreated(new \DateTime("now"));
            $instance->setTimefinish($dataForm['finishdate']);
            $instance->setName($dataForm['name']);
            $instance->setUserId($this->get('session')->get('id'));
            //se comprueba que el seeskey no existe ya en el sistema (improbable pero posible)
            do {
                $seeskey = substr(md5(rand()),0,10);
                $instances = $em->getRepository('IWEasySurveyBundle:Instance')->findBy(array('seeskey'=>$seeskey));
            } while( !empty($instances) );
            
            $instance->setSeeskey($seeskey);
            $em = $this->getDoctrine()->getManager();
            $em->persist($instance);
            $em->flush();
            return $this->redirect($this->generateUrl('iw_easy_survey_instances', array('idProject'=>-1,'idQuiz'=>-1)));
        }
        return $this->render('IWEasySurveyBundle:Quiz:generateinstace.html.twig', array('id' => $id, 'form' => $form->createView()));
    }
    
    private function existDataInArray ($array, $id) {
        foreach ($array as $data) {
            if ($data['id'] == $id) {
                return 1;
            }
        }
        return 0;
    }
    
    public function instancesAction($idProject, $idQuiz) 
    {        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        $projects_results = array();
        $projects_results[] = array('id'=>-1,'name'=>'- Todo -');
        $quiz_results = array();
        $quiz_results[] = array('id'=>-1,'name'=>'- Todo -');
        $datas = array ();
        $em = $this->getDoctrine()->getManager();
        $instances = $em->getRepository('IWEasySurveyBundle:Instance')->findBy(array('userId'=>$this->get('session')->get('id')));
        foreach ($instances as $data) {
            $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($data->getQuizId());
            if (!$this->existDataInArray($quiz_results, $quiz->getId())) {
                $quiz_results[] = array ('id'=>$quiz->getId(),'name'=>$quiz->getName());
            }
            $project = $em->getRepository('IWEasySurveyBundle:Project')->find($quiz->getProjectId());
            if (!$this->existDataInArray($projects_results, $project->getId())) {
                $projects_results[] = array ( 'id'=>$project->getId(), 'name'=>$project->getName() );
            }
            if ( $idProject == -1 ) {
                if ($idQuiz == -1 ) {
                    $datas[]=array(
                            'instance_id'=>$data->getId(),
                            'question'=>$quiz->getName(),
                            'project' =>$project->getName(),
                            'timecreated'=>$data->getTimecreated(),
                            'timefinish'=>$data->getTimefinish(),
                            'seeskey'=>$data->getSeeskey(),
                            'name' => $data->getName()
                    );   
                } else {
                    if ($idQuiz == $quiz->getId()) {
                        $idProject = $quiz->getProjectId();
                        $datas[]=array(
                                'instance_id'=>$data->getId(),
                                'question'=>$quiz->getName(),
                                'project' =>$project->getName(),
                                'timecreated'=>$data->getTimecreated(),
                                'timefinish'=>$data->getTimefinish(),
                                'seeskey'=>$data->getSeeskey(),
                                'name' => $data->getName()
                        );  
                    }
                }
            } else {
                if ( $idProject == $project->getId()) {                    
                    if ($idQuiz == -1 ) {
                     $datas[]=array(
                            'instance_id'=>$data->getId(),
                            'question'=>$quiz->getName(),
                            'project' =>$project->getName(),
                            'timecreated'=>$data->getTimecreated(),
                            'timefinish'=>$data->getTimefinish(),
                            'seeskey'=>$data->getSeeskey(),
                            'name' => $data->getName()
                        );   
                    } else {
                        $idProject = $quiz->getProjectId();
                        if ($idQuiz == $quiz->getId()) {
                            $datas[]=array(
                            'instance_id'=>$data->getId(),
                            'question'=>$quiz->getName(),
                            'project' =>$project->getName(),
                            'timecreated'=>$data->getTimecreated(),
                            'timefinish'=>$data->getTimefinish(),
                            'seeskey'=>$data->getSeeskey(),
                            'name' => $data->getName()
                        );  
                        }
                    }    
                }
            }
        }
        return $this->render('IWEasySurveyBundle:Quiz:instances.html.twig', array('instances'=>$datas,'projects'=>$projects_results,'quizs'=>$quiz_results,'actual_project'=>$idProject,'actual_quiz'=>$idQuiz));
    }
    
    public function deleteInstanceAction($id) 
    {
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $instance = $em->getRepository('IWEasySurveyBundle:Instance')->find($id);
        if ($instance->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        $em->remove($instance);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_instances', array('idProject'=>-1,'idQuiz'=>-1)));
    }
    
    private function getOptions($id) 
    {
        $em = $this->getDoctrine()->getManager();
        $options = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->findBy(array('questionId'=>$id));
        $options_array = array ();
        foreach ($options as $value) {
            $options_array[$value->getId()] = $value->getText();
        }
        return $options_array;
    }

    public function replyQuizAction($seeskey, Request $request)
    {   
        $formBuilderQuestionnaire  = $this->createFormBuilder();   
        $em = $this->getDoctrine()->getManager();
        $instance = $em->getRepository('IWEasySurveyBundle:Instance')->findBy(array('seeskey'=>$seeskey));
        $actualDate = new \DateTime("now");
        $finishdate = $instance[0]->getTimefinish();
        $close = 0;
        if ($finishdate<$actualDate) {
            $close = 1;
        }
        
        $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->find($instance[0]->getQuizId());
        $questions = $em->getRepository('IWEasySurveyBundle:Question')->findBy(array('quizId'=>$quiz->getId()));
        foreach ($questions as $data) {
            switch ( $data->getTypeId() ) {
                //numerica
                case 0:     $formBuilderQuestionnaire->add($data->getTypeId().'_'.$data->getId(), 
                                    'number',  array('required' => true,'label' => $data->getName()));
                            break;
                
                //texto
                case 1:     $formBuilderQuestionnaire->add($data->getTypeId().'_'.$data->getId(), 
                                    'text',  array('required' => true,'label' => $data->getName()));
                            break;
                //selección simple
                case 2:     $options_array = $this-> getOptions($data->getId());
                            $formBuilderQuestionnaire->add($data->getTypeId().'_'.$data->getId(), 'choice',  
                                    array('required' => true,'label' => $data->getName(),'choices' => $options_array));
                            break;
                //selección multiple
                case 3:     $options_array = $this-> getOptions($data->getId());
                            $formBuilderQuestionnaire->add($data->getTypeId().'_'.$data->getId(), 'choice',  array('required' => true, 'label' => $data->getName(),'multiple'=>true,'choices' => $options_array,));
                            break;
            }
        }
        $formBuilderQuestionnaire->add('Captcha', 'recaptcha');
        $formBuilderQuestionnaire->add('add', 'submit', array('label' => 'Enviar'));
        $form = $formBuilderQuestionnaire->getForm();
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            $dataForm = $form->getData();
            
            foreach ($dataForm as $key => $value) {                
                if ($key!='Captcha') {
                    $aux = explode('_',$key);
                    $type = $aux[0];
                    $quizId = $aux[1];
                    $answer = new \IW\EasySurveyBundle\Entity\Answers;
                    $answer->setIdQuestion($quizId);
                    if ($type == 3) {
                        $result = '';
                        foreach ($value as $a) {
                            $result .= $a.',' ;
                        }
                        if ($result!='') {
                            $result = substr($result, 0, strlen($result)-1);
                        }
                    } else {
                        $result = $value;
                    }
                    $answer->setBody($result);
                    $answer->setTimestamp(new \DateTime("now"));
                    $answer->setIdInstance($instance[0]->getId());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($answer);
                    $em->flush();
                }
            }
            return $this->render('IWEasySurveyBundle:Quiz:replySend.html.twig', array());
        }
        return $this->render('IWEasySurveyBundle:Quiz:reply.html.twig', array('name'=>$quiz->getName(), 'form' => $form->createView(),'close'=>$close));
    }    
    
    public function closeInstanceAction($id){
        
        if (!$this->isLogin()) {            
            return $this->redirect($this->generateUrl('iw_easy_survey_error_login',array()));            
        }
        
        $em = $this->getDoctrine()->getManager();
        $instance = $em->getRepository('IWEasySurveyBundle:Instance')->find($id);
        
        if ($instance->getUserId()!=$this->get('session')->get('id')) {
            return $this->redirect($this->generateUrl('iw_easy_survey_error_access',array()));
        }
        
        $instance->setTimefinish(new \DateTime("now"));
        $em->persist($instance);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_instances', array('idProject'=>-1,'idQuiz'=>-1)));
    }
    
}

