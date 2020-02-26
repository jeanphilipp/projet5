<?php
namespace App\Controller\Back;
use App\Entity\Booking;
use App\Entity\Cat;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class CatController extends AbstractController
{
    /**
     * @Route("/admin/cats", name="admin_list_cats")
     */
    public function listingCats()
    {
        $cats = $this->getDoctrine()->getRepository(Cat::class)->findAll();
        $bookings = $this->getDoctrine()->getRepository(Booking::class)->findAll();
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();
        return $this->render('back/cat/list.html.twig',[
            'cats' => $cats,
            'bookings' => $bookings,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/admin/cats/show/{id}")
     */
   // public function show(int $id)
   // {
     //   $cat = $this->getDoctrine()->getRepository(Cat::class)->find($id);
        //return $this->render('back/cat/show.html.twig', [
          //  'cat' => $cat
      //  ]);
   // }
}