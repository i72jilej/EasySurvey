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
            $project->setUserId($this->get('session')->get('id'));
            $em = $this->getDoctrine()->getManager();
            //hay que comprobar que el seeskey generado no exista ya en el sistema (improbable pero posible)
            do {
                $seeskey = substr(md5(rand()),0,10);    
                $quiz = $em->getRepository('IWEasySurveyBundle:Quiz')->findby(array('sesskey' => $seeskey));
            } while (!empty($quiz));
            $project->setSesskey($seeskey);
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
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_quiz'));
    }

    public function manageQuestionsAction($id) {
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
    
    private function getTypeQuestionsDefinitions() {
        $definitions = array();
        $definitions[0] = 'El encuestado introducirá un valor numérico por teclado';
        $definitions[1] = 'El encuestado introducirá una respuesta escrita por teclado';
        $definitions[2] = 'El encuestado elegirá una única respuesta entre las presentadas';
        $definitions[3] = 'El encuestado podrá elegir varias respuestas entre las presentadas';
        return $definitions;
    }
    
    public function addQuestionAction($id, Request $request) {

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
            if ( $change ) {
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_questions', array('id' => $question->getQuizId())));
            } else {
                return $this->redirect($this->generateUrl('iw_easy_survey_manage_question_option', array('id' => $question->getId())));
            }
        }

        return $this->render('IWEasySurveyBundle:Quiz:addQuestion.html.twig', array('id' => $id, 'form' => $form->createView()));
    }

    public function deleteQuestionAction($id) {
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
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
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
        $options = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->findBy(array('questionId' => $id ));
        return $this->render('IWEasySurveyBundle:Quiz:editQuestionOption.html.twig', array('id' => $id, 'options' => $options,'question'=>$question));
    }
    
    public function addOptionAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($id);
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
        
        $em = $this->getDoctrine()->getManager();
        $option = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->find($id);
        $question = $em->getRepository('IWEasySurveyBundle:Question')->find($option->getQuestionId());
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
        $em = $this->getDoctrine()->getManager();
        $option = $em->getRepository('IWEasySurveyBundle:TextQuestionOption')->find($id);
        $em->remove($option);
        $em->flush();
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_question_option', array('id' => $option->getQuestionId())));        
    }
    
    

}
