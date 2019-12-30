<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class,[
                'attr' => [
                    'placeholder' => "Ecrivez votre avis ici"
                ]
            ])
            ->add('Envoyer', SubmitType::class,[
            'attr' => ['class' => 'btn btn-secondary'],
]);
        /* $builder->add('createdAt', DateType::class, [
             'label' => 'Date de création'
         ]);*/
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Comment::class
        ));
    }
}