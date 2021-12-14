<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => "Nom complet",
                'attr' => [
                    'placeholder' => "Saisir le nom complet."
                ]
            ])
            ->add('address', TextareaType::class, [
                'label' => "Adresse",
                'attr' => [
                    'placeholder' => "Saisir mon adresse."
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => "Code Postal",
                'attr' => [
                    'placeholder' => "Saisir le code postal de ma commune."
                ]
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => [
                    'placeholder' => "Saisir ma ville"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Purchase::class
        ]);
    }
}
