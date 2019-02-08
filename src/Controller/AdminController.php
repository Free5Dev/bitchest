<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Form\RegistrationType;
use App\Entity\Users;

class AdminController extends AbstractController
{
    
    /**
     * @Route("/", name="admin_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username=$utils->getLastUsername();
        return $this->render('admin/login.html.twig',[
            'hasError'=>$error !==null,
            'username'=>$username
        ]);
    }
    /**
     * @Route("/logout", name="admin_logout")
     * 
     * @return void
     */
    public function logout(){

    }
    /**
     * @Route("/admin/users", name="users")
     */
    public function users(){
        $users= new Users();
        $repo=$this->getDoctrine()->getRepository(Users::class);
        $users=$repo->findAll();
        return $this->render('admin/users.html.twig',[
            'users'=>$users
        ]);
    }
    /**
     * @Route("/admin/users/add", name="users_add")
     */
    public function usersAdd(Users $users=null, Request $request, ObjectManager $manager){
        if(!$users){
            $users= new Users();
        }

        $form=$this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($users);
            $manager->flush();
            return $this->redirectToRoute("users");
        }
        return $this->render('admin/usersAdd.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
