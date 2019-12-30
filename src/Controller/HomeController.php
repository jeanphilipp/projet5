<?php

namespace App\Controller;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage()
    {
        //$bookings = $this->getDoctrine()->getRepository(Booking::class)->findAll();
        //dump($bookings);
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
            ]);
    }
    /**
     * @Route("/reservation", name="reservation")
     */
    public function booking(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $bookings = $entityManager->getRepository(Booking::class)->findAll();
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           $interval = date_diff($booking->getStartDate(), $booking->getExitDate());
            $nb_jours = (int)$interval->format('%R%a');
           //dump($nb_jours);die;
            $booking->setPriceStay($nb_jours * 10);

            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('reservation');
        }

        return $this->render('reservation.html.twig', [

            'form' => $form->createView(),
            'bookings' => $bookings]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginUser()
    {
        return $this->render('login.html.twig');
    }
}

