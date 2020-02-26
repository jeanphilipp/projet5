<?php
namespace App\Form;
use App\Entity\Booking;
use App\Entity\Cat;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       // dump($options);die;
       $user = $options['user'];
        $builder
            ->add('cat', EntityType::class, array(
                'label' => 'Votre chat :',
                'class' => Cat::class,
                'choice_label' => 'catName',

                /*ajout fonction anonyme */
                'query_builder' => function (EntityRepository $er)use($user) {
                   return $er->createQueryBuilder('c')
                       ->where('c.user = :user')->setParameter(':user', $user);
              },
            ))
            ->add('startDate', DateType::class,
                [
                    'label' => "Date d'entrée",
                    // renders it as a single text box
                    'widget' => 'single_text',
                ])
            ->add('exitDate', DateType::class,
                [
                    'label' => "Date de sortie",
                    'widget' => 'single_text',
                ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-secondary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Booking::class,

            'user' => null,

        ));



    }
}