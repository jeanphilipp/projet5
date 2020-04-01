<?php
namespace App\Controller;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\AskForResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/registration", name="security_registration")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $errors = [];
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $message = "";
        if ($form->isSubmitted() && $form->isValid()) {
            $hash  = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            try {
                $entityManager->flush();
                 $message = "Félicitation, votre compte a été créé ! Vous pouvez vous connecter.";
            } catch (UniqueConstraintViolationException $e) {
                $errors[] = "Ce pseudo a déja été utilisé !";
            }
        }
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors,
            'message' => $message
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/ask-for-reset", name="app_ask_for_reset")
     */
    public function askForResetPassword(Request $request, MailerInterface $mailer,TokenGeneratorInterface $tokenGenerator)
    {

       // On crée le formulaire
        $form = $this->createForm(AskForResetPasswordType::class);
        // On traite le formulaire
        $form->handleRequest($request);
        // si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid())
        {
            // On récupère les données
            $donnees = $form->getData();
            // On cherche si un user a cet émail
            $user = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($donnees['email']);
            // Si l'user n'existe pas
            if(!$user)
            {
                // On envoie un message flash
                $this->addFlash('danger', 'Cette adresse Email n\'existe pas !');
                return $this->redirectToRoute('app_login');

            }

            // On génère un token
            $token = $tokenGenerator->generateToken();
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }catch(\Exception $e){
                $this->addFlash('warning', 'Une erreur est survenue :'. $e->getMessage());
                return $this->redirectToRoute('app_login');
            }
            // On génère l'URL de mot de passe

            $url = $this->generateUrl('app_reset_password', ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL);

            // On envoie le message
           // $message = (new \Swift_Message('Mot de passe oublié'))
            //$message = (new Mailer('Mot de passe oublié'))
            $email =  (new Email())
            ->from('jpgscn@gmail.com')
            /*->to($user->getEmail())*/
            ->to($user->getEmail())
            ->subject('reset de mot de passe')
            ->html(
                '<p>Bonjour</p><p> Une demande de réinitialisation de mot de passe a été effectué pour le site Pension Féline Le
Chat Botté. Veuillez cliquer sur le lien suivant :' . $url . '</p>', 'text/html'
            );
            // On envoie l'email
            $mailer->send($email);
            // On crée le message flash
            $this->addFlash('message', 'Un émail de réinitialisation de mot de passe vous a été envoyé');
            return $this->redirectToRoute('app_login');
        }
// On envoie vers la page de demande de l'émail
        return $this->render('security/forgotten_password.html.twig', ['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/reset-pass/{token}", name="app_reset_password")
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // On cherche l'utilisateur avec le token fourni
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token'=>$token]);

        if(!$user)
        {
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('app_login');
        }
        // On vérifie si le formulaire est envoyé en methode post
        if($request->isMethod('POST'))
        {
            // On supprime le token
            $user->setResetToken(null);
            // On chiffre le mot de passe
            $user->getEmail($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Mot de passe changé avec succès !');

            return $this->redirectToRoute('app_login');
        }
        else
        {
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }
}

















