<?php


namespace App\Controller\Back;


use App\Entity\Cat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CatController extends AbstractController
{
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

    public function create()
    {
//
    }

    public function edit()
    {

    }

    public function delete()
    {

    }
}