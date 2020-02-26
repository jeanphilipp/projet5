<?php
namespace App\Controller\Back;
use App\Entity\Cat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function admin()
    {
        $cats = $this->getDoctrine()->getManager()->getRepository(Cat::class)->findAll();
        return $this->render('back/cat/list.html.twig', [
            'cats' => $cats,
        ]);
    }
}