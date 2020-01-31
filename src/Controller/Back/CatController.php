<?php

namespace App\Controller\Back;

use App\Entity\Booking;
use App\Entity\Cat;
use App\Entity\User;
use App\Form\CatType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CatController extends AbstractController
{
    /**
     * @Route("/admin/cats", name="admin_list_cats")
     */
    public function listingCats()
    {
      //findAll
        $cats = $this->getDoctrine()->getRepository(Cat::class)->findAll();
        //ajout recuperer les users
       /* $pseudo =$this->getDoctrine()->getRepository(User::class)->findAll();*/
        $bookings = $this->getDoctrine()->getRepository(Booking::class)->findAll();
       // $booking = new Booking();
        return $this->render('back/cat/list.html.twig',[
            'cats' => $cats,
            'bookings' => $bookings,
        ]);
    }

    /**
     * @Route("/admin/cats/show/{id}")
     */
    public function show(int $id)
    {
        //dump($id);die;
        $cat = $this->getDoctrine()->getRepository(Cat::class)->find($id);
        // dump($cat);die;
        return $this->render('back/cat/show.html.twig', [
            'cat' => $cat
        ]);
    }

    /**
     * @Route("/admin/cats/create")
     */
    public function create(Request $request)
    {
        //creation formulaire
        // ici a travailler appeler form avec CatType
        $entityManager = $this->getDoctrine()->getManager();
        $cat = new Cat();
        $form = $this->createForm(CatType::class, $cat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cat);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_cats');
        }
        return $this->render('back/cat/new.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }


    /**
     * @Route("/admin/cats/edit/{id}")
     */
    public function edit(Request $request, int $id)
    {
        //find(id)
        $entityManager = $this->getDoctrine()->getManager();
        $cat = $entityManager->getRepository(Cat::class)->find($id);
        $form = $this->createForm(CatType::class, $cat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cat);
            $entityManager->flush();

            return $this->redirectToRoute('admin_list_cats');
        }
        return $this->render('back/cat/new.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/admin/cats/delete/{id}")
     */
    public function delete()
    {

    }
}