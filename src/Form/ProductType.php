<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image as ImageCon;

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
                'required'=> false,
                'mapped'=> false,
                'constraints' => $this->getImageConstraints(),
                ])
            ->add('imageGallery',FileType::class,[
                'label' => 'Add file to gallery',
                'required'=> false,
                'mapped'=> false,
                'constraints' => $this->getImageConstraints(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    public function getImageConstraints(): array
    {
        $imageConstraints = [
            new ImageCon([
                'maxSize' => '1024k',
                'mimeTypes' => [
                    'image/png',
                    'image/jpg',
                ],
                'mimeTypesMessage' => 'Please upload a valid image document',
            ])
        ];

        return $imageConstraints;
    }
}
