<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Groupes;
use App\Repository\UserRepository;
use App\Repository\GroupesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController'
            ,
        ]);
    }
    /**
     * @Route("/listing", name="listing")
     */
    public function listing(UserRepository $repo)
    {
        
        $users = $repo->findAll();

    	return $this->render('home/listing.html.twig',[
        'users' => $users,
        ]);
    }
    /**
     * @Route("/new_user", name="addUser")
     * @Route("/{id}/user_edit", name="user_edit")
     */
    public function User(User $user = null, Request $request, ObjectManager $manager,GroupesRepository $repo)
    {
        $groupes = $repo->findAll();
         if(!$user){
            $user = new User();
        }
        
        $form = $this->createFormBuilder($user)
                     ->add('first_name')
                     ->add('last_name')
                     ->add('groupes', EntityType::class, [
                        'class' => Groupes::class,
                            'choice_label' =>'name',
                            'multiple' =>true ])
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

           if(!$user->getId()){
               $user->setCreatedAt(new \DateTime());

             }

            $user ->setCreatedAt(new \DateTime());
            $manager->persist($user);
            $manager->flush();

          return $this->redirectToRoute('listing');
        }

    	return $this->render('home/user.html.twig', [
                              'formUser' =>$form->createView(),
                              'formGroup' => $groupes
        ]);
    }

    /**
     * @Route("/new_group", name="addGroup")
     */
    public function Groupes( Request $request, ObjectManager $manager)
    {
    
        $group = new Groupes();
    
        $form = $this->createFormBuilder($group)
                     ->add('name')    
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($group);
            $manager->flush();

          return $this->redirectToRoute('listing');
        }

        return $this->render('home/groupe.html.twig', [
                              'formGroup' =>$form->createView()
        ]);
    	
    }
}
