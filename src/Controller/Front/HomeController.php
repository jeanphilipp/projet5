<?php

namespace App\Controller\Front;

use App\Entity\Booking;
use App\Entity\Cat;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CatType;
use App\Form\CommentType;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home_homepage")
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
        $entityManager = $this->getDoctrine()->getManager();
        $comments = $entityManager->getRepository(Comment::class)->findAll();
        $comment = new Comment();
        $user = $security->getUser();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //user connecté
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
     * @Route("/cats/create", name="app_home_create")
     * Inscrire un chat via le formulaire
     */
    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cat = new Cat();
        $form = $this->createForm(CatType::class, $cat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cat->setUser($this->getUser());
            $entityManager->persist($cat);
            $entityManager->flush();
            return $this->redirectToRoute('booking');
        }
        return $this->render('front/cat/new.html.twig',
            ['form' => $form->createView(),
                'cat' => $cat
            ]);
    }

    /**
     * @Route("/booking", name="booking")
     */
    public function booking(Request $request, Security $security)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cats = $entityManager->getRepository(Cat::class)->findBy(['user' => $security->getUser()]);
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking, ['user' => $security->getUser()]);
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
                'user' => $security->getUser(),
                'cats' => $cats,
            ]
        );
    }

    /**
     * @Route("/booking/update/{id}", name="app_booking_update")
     */
    public function update(Booking $booking, Request $request, Security $security)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(BookingType::class, $booking, ['user' => $security->getUser()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* Calcul du prix du sejour*/
            $interval = date_diff($booking->getStartDate(), $booking->getExitDate());
            $nb_jours = (int)$interval->format('%R%a');
            $booking->setPriceStay($nb_jours * 10);
            $entityManager->persist($booking);
            $entityManager->flush();
            return $this->redirectToRoute('booking');
        }
        return $this->render('front/cat/update.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/booking/delete/{id}", name="app_booking_delete")
     */
    public function delete(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $booking = $entityManager->getRepository(Booking::class)->find($id);
        if ($booking instanceof Booking) {
            $entityManager->remove($booking);
            $entityManager->flush();
        }
        return $this->redirectToRoute('booking');
    }

    /**
     * @Route("/contact" , name="app_contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        $info_contact = "";
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $info_contact = " Votre message a bien été envoyé";
            $email = (new TemplatedEmail())
                ->from('jpgscn@gmail.com')
                ->to('jpgscn@gmail.com')
                ->subject($data['objet'])
                // path of the Twig template to render
                ->htmlTemplate('email/contact.html.twig')
                // pass variables (name => value) to the template
                ->context([
                    'data' => $data,
                ]);
            $mailer->send($email);
        }
        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
            'info_contact' => $info_contact,
        ]);
    }
}