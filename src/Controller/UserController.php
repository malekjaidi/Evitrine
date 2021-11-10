<?php

namespace App\Controller;
use App\Form\ModifyUserType;
use App\Form\ModifyType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="userlist")
     */    
    
    public function index(): Response
    {
        $repo=$this->getDoctrine()->getRepository(User::class);
        $users=$repo->findAll();
        return $this->render('user/index.html.twig', ['User' => $users]);
    }

    #[Route('/register', name: 'register')]
    public function registerpage(): Response
    {
        return $this->render('user/register.html.twig');
    }

    #[Route('/test', name: 'test')]
    public function testpage(): Response
    {
        return $this->render('user/test.html.twig');
    }

    

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): response
    {
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        return $this->render('product/index.html.twig');


    }

     /**
     * @Route("/user/delete/{id}", name="deleteuser")
     */
    public function deleteuser($id): Response
    {
        $repo=$this->getDoctrine()->getRepository(User::class);
        $users=$repo->find($id);
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($users);
        $manager-> flush();
        #return $this->render('product/index.html.twig') ;
        return new Response("supression Validée ");
        }



        

    /**
* @Route("/user/modify/{id}", name="modifyuser")
*/

public function modifyUser(User $User,Request $request,$id): Response
{
    $form = $this->createForm(ModifyType::class, $User);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
        // but, the original $task variable has also been updated
        $User = $form->getData();


        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($User); 
        $entityManager->flush();
        $repo=$this->getDoctrine()->getRepository(User::class);
        $User=$repo->find($id);
        return new Response("Modification Validée ");

        
    }
    return $this->renderForm('user/modify.html.twig', [
        'formpro' => $form,
    ]);
}
    
    
  
}
