<?php

namespace App\Form;

use App\Entity\ReservationWorkshop;
use App\Entity\Workshop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReservationWorkshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie')
            ->add('date_reservation')
            ->add('status')
           
            ->add('workshops', EntityType::class, [
                'class' => workshop::class,
                'choice_label' => 'categorie',
                'placeholder' => 'Choose a workshop'
            ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationWorkshop::class,
        ]);
    }
}
