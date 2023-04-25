<?php

namespace App\Form;

use App\Entity\Workshop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class WorkshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('nom_artiste')
            ->add('duree')
            ->add('date')
            ->add('heure_debut')
            ->add('heure_fin')
            ->add('nb_places')
            ->add('categorie')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'votre image de profil (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '100000k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])
           
            

            
        
        
            ->add('etat')
            ->add('niveau', ChoiceType::class, [
                'choices' => [
                    'Facile' => 'Facile',
                    'Intermidiaire' => 'Intermidiaire',
                    'Avancé' => 'Avancé',
                ],
            ])
           
            ->add('prix')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workshop::class,
        ]);
    }
}
