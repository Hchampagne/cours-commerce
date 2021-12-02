<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => 'Nom du produit',
            'attr' => ['placeholder' => 'Entrez le nom du prodit']
        ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'description',
                'attr' => ['placeholder' => 'Entrez la description du produit']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du Prduit en ',
                'attr' => ['placeholder' => 'Tapez le prix du produit en €'],
                'divisor' => '100'
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'image du produit',
                'attr' => ['placeholder' => 'Entrez une uRL d\'image']
            ])
            ->add(
                'category',
                EntityType::class,
                [
                    'label' => 'Catégorie',
                    'placeholder' => '--choisr une catégorie--',
                    'class' => Category::class,
                    'choice_label' => function (Category $category) {
                        return strtoupper($category->getName());
                    }
                ]
            );

        //$builder->get('price')->addModelTransformer(new CentimesTransformer);


        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

        //    $form = $event->getForm();

        //    /** @var Product */
        //$product = $form->getData();

        //dd($product);

        //     if ($product->getId() === null) {

        //         $form->add(
        //             'category',
        //             EntityType::class,
        //             [
        //                 'label' => 'Catégorie',
        //                 'placeholder' => '--choisr une catégorie--',
        //                 'class' => Category::class,
        //                 'choice_label' => function (Category $category) {
        //                     return strtoupper($category->getName());
        //                 }
        //             ]
        //         );
        //     }
        //});
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
