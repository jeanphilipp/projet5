<?php


namespace App\Controller;

use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/* use Symfony\Component\HttpFoundation\Response; */
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage()
    {
        $bookings = $this->getDoctrine()->getRepository(Booking::class)->findAll();
        //dump($bookings);
        return $this->render('home.html.twig', [
            'bookings' => $bookings,

        ]);
    }

    /**
     * @Route("/aboutus", name="about_us")
     */
    public function aboutUs()
    {
        //return new Response("OK");
        return $this->render('aboutus.html.twig');
    }
    //continuer route ici



}

