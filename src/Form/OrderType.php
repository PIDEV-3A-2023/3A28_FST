<?php

namespace App\Form;

use App\Entity\Shoppingcart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre nom.']),
                    new Length(['max' => 30, 'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.']),
                ],
            ])
            ->add('prenoom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre prénom.']),
                    new Length(['max' => 255, 'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.']),
                ],
            ])
            ->add('ville', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer le nom de votre ville.']),
                    new Length(['max' => 40, 'maxMessage' => 'Le nom de la ville ne peut pas dépasser {{ limit }} caractères.']),
                ],
            ])
            ->add('adresse', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre adresse.']),
                    new Length(['max' => 40, 'maxMessage' => 'L\'adresse ne peut pas dépasser {{ limit }} caractères.']),
                ],
            ])
            ->add('code_postale', TextType::class, [
                'constraints' => [
                   
                    new Length(['max' => 10, 'maxMessage' => 'Le code postal ne peut pas dépasser {{ limit }} caractères.']),
                    new Regex([
                        'pattern' => '/^\d{5}$/',
                        'message' => 'Le code postal doit être composé de 5 chiffres.',
                    ]),
                ],
            ])
            ->add('orderDate')
            ->add('Total_price')
            ->add('sta')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shoppingcart::class,
        ]);}}