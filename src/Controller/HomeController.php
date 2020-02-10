<?php
namespace App\Controller;
use App\Entity\Booking;
use App\Entity\Cat;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CatType;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
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
    public function aboutUs(Request $request, Security $security)
    {
        //dump($security->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $comments = $entityManager->getRepository(Comment::class)->findAll();
        $comment = new Comment();
        //Ajout composant user = Security
        $user = $security->getUser();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            //user connecté !!
            $comment->setUser($user);
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
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $interval = date_diff($booking->getStartDate(), $booking->getExitDate());
            $nb_jours = (int)$interval->format('%R%a');
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

    /**
     * @Route("/booking/update/{id}", name="app_booking_update")
     */
    public function update(Booking $booking, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($booking);
            $entityManager->flush();
            return $this->redirectToRoute('booking');
        }

        return $this->render('front/cat/update.html.twig', [
             'booking' => $booking,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/booking/delete/{id}", name="app_booking_delete")
     */
    public function delete(int $id)
    {
        {
            $entityManager = $this->getDoctrine()->getManager();
            $booking = $entityManager->getRepository(Booking::class)->find($id);
            if ($booking instanceof Booking) {
                $entityManager->remove($booking);
                $entityManager->flush();
            }
            return $this->redirectToRoute('booking');
        }
    }
}

