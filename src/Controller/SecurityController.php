<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    //public function registration(Request $request, ObjectManager $manager)
    public function registration(Request $request)
    {
        $errors = [];

       $user = new User();
       $form = $this->createForm(RegistrationType::class, $user);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid())
       {
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($user);

           try {
               $entityManager->flush();
           }
           catch(UniqueConstraintViolationException $e ){
            $errors[] = "Ce pseudo a déja été utilisé !";
           }

       }

       return $this->render('security/registration.html.twig', [

           'form' => $form->createView(),
           'errors' => $errors,
       ]);
    }
}
