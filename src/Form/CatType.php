<?php

namespace App\Form;
use App\Entity\Cat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CatType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('catName');
    }

    public function configureOptions(OptionsResolver $resolver)
   {
       $resolver->setDefaults(array(
       'data_class' => Cat::class
       ));
   }
}