<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AskForResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,
                [
                    'attr' => ['placeholder' => 'Votre Email']
                ])
            ->add('Envoyer', SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-secondary'],
                ]);
    }
}


