<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('category', EntityType::class,[
                        'class'=>ProductCategory::class,
                        'choice_label'=>'name'])
            ->add('Image', FileType::class,[
                'label'=>'Load Main image file',
                'required' => false,
                'mapped'=>false,
                ])
            ->add('imageGallery',FileType::class,[
                'label' => 'Load many files ',
                'multiple'=>true,
                'required' => false,
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
//                'constraints' => [
//                    new Image([
//                        'maxSize' => '1024k',
//                        'mimeTypes' => [
//                            'image/png',
//                            'image/jpg',
//                        ],
//                        'mimeTypesMessage' => 'Please upload a valid image document',
//                    ])
//                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
