<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Cat;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\BookingType;
use App\Form\CatType;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/aboutus", name="about_us")
     */
    public function aboutUs(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comments = $entityManager->getRepository(Comment::class)->findAll();
        $comment = new Comment();
        $user = new User();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('about_us');
        }
        return $this->render('aboutus.html.twig',
            [
                'form' => $form->createView(),
                'comments' => $comments,
                'user' => $user
            ]);
    }


    /**
     * @Route("/booking", name="booking")
     */
    public function booking(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $bookings = $entityManager->getRepository(Booking::class)->findAll();
        $cats = $entityManager->getRepository(Cat::class)->findAll();
        $booking = new Booking();

       // $cat = new Cat();

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $interval = date_diff($booking->getStartDate(), $booking->getExitDate());
            $nb_jours = (int)$interval->format('%R%a');
            //dump($nb_jours);die;
            $booking->setPriceStay($nb_jours * 10);
            $entityManager->persist($booking);
            $entityManager->flush();
            return $this->redirectToRoute('booking');
        }
        return $this->render('booking.html.twig', [
                'form' => $form->createView(),
                'bookings' => $bookings,
                'cats' => $cats
            ]
        );
    }

    /**
     * @Route("/cats/create")
     */
    public function create(Request $request)
    {
       // dump($this->getUser()->getId()); die;
        $entityManager = $this->getDoctrine()->getManager();

        $cat = new Cat();
        $form = $this->createForm(CatType::class, $cat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            //ne pas oublier
            $cat->setUser($this->getUser());
            $entityManager->persist($cat);

            $entityManager->flush();
            return $this->redirectToRoute('booking');
        }
        return $this->render('front/cat/new.html.twig',
            [   'form' => $form->createView(),
                'cat' => $cat
            ]);
    }

}

