<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function listAction()
    {
        $contacts = $this->getDoctrine()
                ->getRepository('AppBundle:Contact')
                ->findAll();
        return $this->render('employee/index.html.twig', array(
            'contacts'=>$contacts
        ));
    }
    /**
     * @Route("/employee/create", name="create")
     */
    public function createAction(Request $request)
    {
        $contact = new Contact;
        $form = $this->createFormBuilder($contact)
                ->add('name', TextType::class,array('attr' => array('class' => 'form-control','style' =>'margin-bottom:15px')))
                ->add('lname',TextType::class,array('attr'=> array('class' =>'form-control','style' =>'margin-bottom:15px')))
                ->add('email',EmailType::class,array('attr'=> array('class' =>'form-control','style' =>'margin-bottom:15px')))
                ->add('Add',SubmitType::class,array('label'=> 'Create Contact','attr'=> array('class' =>'btn btn primary','style' =>'margin-bottom:15px')))

                ->getForm();
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $name =$form['name']->getData();
            $lname =$form['lname']->getData();
            $email =$form['email']->getData();
            $contact->setName($name);
            $contact->setLname($lname);
            $contact->setEmail($email);
            
            $em=$this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            
            $this->addFlash(
                    'notice',
                    'Contact Added'
                    );
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('employee/create.html.twig',array('form'=> $form->createView()));
    }
    /**
     * @Route("/employee/edit/{id}", name="edit")
     */
    public function editAction($id,Request $request)
    {
          $contact = $this->getDoctrine()
                ->getRepository('AppBundle:Contact')
                ->find($id);
           $contact->setName($contact->getName());
            $contact->setLname($contact->getLname());
            $contact->setEmail($contact->getEmail());
                  $form = $this->createFormBuilder($contact)
                ->add('name',TextType::class,array('attr'=> array('class' =>'form-control','style' =>'margin-bottom:15px')))
                ->add('lname',TextType::class,array('attr'=> array('class' =>'form-control','style' =>'margin-bottom:15px')))
                ->add('email',EmailType::class,array('attr'=> array('class' =>'form-control','style' =>'margin-bottom:15px')))
                ->add('Add',SubmitType::class,array('label'=> 'Update Contact','attr'=> array('class' =>'btn btn primary','style' =>'margin-bottom:15px')))

                ->getForm();
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $name =$form['name']->getData();
            $lname =$form['lname']->getData();
            $email =$form['email']->getData();
            
             $em=$this->getDoctrine()->getManager();
             $contact = $em->getRepository('AppBundle:Contact')->find($id);
            $contact->setName($name);
            $contact->setLname($lname);
            $contact->setEmail($email);
            
           
            
            $em->flush();
            
            $this->addFlash(
                    'notice',
                    'Contact Updated'
                    );
            return $this->redirectToRoute('homepage');
        }
           return $this->render('employee/edit.html.twig', array(
            'contact'=>$contact,
                   'form' =>$form->createView()
                   
        ));
        
    }
    /**
     * @Route("/employee/details/{id}", name="detail")
     */
    public function detailAction($id)
    {
        
        $contact = $this->getDoctrine()
                ->getRepository('AppBundle:Contact')
                ->find($id);
        return $this->render('employee/details.html.twig', array(
            'contact'=>$contact
        ));
        
    }
    
     /**
     * @Route("/employee/delete/{id}", name="delete")
     */
    public function deleteAction($id)
    {
        
        $em=$this->getDoctrine()->getManager();
             $contact = $em->getRepository('AppBundle:Contact')->find($id);
             $em->remove($contact);
             $em->flush();
    
            
            $this->addFlash(
                    'notice',
                    'Contact removed'
                    );
            return $this->redirectToRoute('homepage');
}
}
