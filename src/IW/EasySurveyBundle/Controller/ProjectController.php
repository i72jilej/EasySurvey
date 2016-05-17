<?php

namespace IW\EasySurveyBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    public function createAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('Nombre', 'text', array('required'=>true))
            ->add('Descripcion', 'textarea', array('required'=>true))
            ->add('Crear', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $user_id = $this->get('session')->get('id');
            $projectData = $form->getData();
            $project = new \IW\EasySurveyBundle\Entity\Project;
            $project->setName($projectData['Nombre']);
            $project->setDescription($projectData['Descripcion']);
            $project->setUserId($user_id);
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();    
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_project'));
        }
        
        return $this->render('IWEasySurveyBundle:Project:form.html.twig', array('form' => $form->createView(),));
    }
    
    public function manageAction() {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('IWEasySurveyBundle:Project')->findby(array('user_id'=>$this->get('session')->get('id')));
        return $this->render('IWEasySurveyBundle:Project:manage.html.twig', array('projects'=>$projects));
    }
    
    public function deleteAction (Request $request) {
        return $this->redirect($this->generateUrl('iw_easy_survey_manage_project'));
    }
    
    
    private function getAllUser () {
        $em = $this->getDoctrine()->getManager();
        
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
        
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('IWEasySurveyBundle:Project')->find($id);
        
        $arrayUser = $this->getAllUser();
        $user_selecteds = $this->getUserProjectSelected($id);
        
        $form = $this->createFormBuilder()
            ->add('Nombre', 'text', array('required'=>true, 'data'=>$project->getName()))
            ->add('Descripcion', 'textarea', array('required'=>true, 'data'=>$project->getDescription()))
            ->add('Colaboradores','choice',array('label'=>'Colaboradores','choices'=>$arrayUser,'data'=>$user_selecteds,'multiple'=>true))
            ->add('Modificar', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $projectData = $form->getData();
            
            //se eliminan los colaboradores existentes
            $projectUser = $em->getRepository('IWEasySurveyBundle:ProjectUser')->findBy(array('projectId'=>$id));
            foreach ($projectUser as $data) {
                $em->remove($data);
                $em->flush();
            }
            
            //se crean los nuevo colaboradores
            foreach ($projectData['Colaboradores'] as $data) {
                $projectUser = new \IW\EasySurveyBundle\Entity\ProjectUser;
                $projectUser->setProjectId($id);
                $projectUser->setUserId($data);
                $em->persist($projectUser);
                $em->flush();   
            }
            
            $project->setName($projectData['Nombre']);
            $project->setDescription($projectData['Descripcion']);
            $em->flush();    
            return $this->redirect($this->generateUrl('iw_easy_survey_manage_project'));
        }
        
        return $this->render('IWEasySurveyBundle:Project:form.html.twig', array('form' => $form->createView(),));
    }
}
