<?php

namespace App\Controller\Back;

use App\Entity\Cat;
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
    public function edit()
    {

    }

    /**
     * @Route("/admin/cats/delete/{id}")
     */
    public function delete()
    {

    }
}