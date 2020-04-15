<?php
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    // Form pour changer le mot de passe oublié
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      /*  $builder
           // ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('confirm_password',PasswordType::class)
            ->add('Valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-secondary'],
            ]);*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => User::class
        ]);
    }

}