<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\BookGenre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description',TextareaType::class)
            ->add('author',EntityType::class,[
                'class'=>Author::class,
                'choice_label'=> function($author){
                    return $author->getName()." ".$author->getSurname();
                }
            ])
            ->add('genre',EntityType::class,[
                'class'=>BookGenre::class,
                'choice_label'=>'name'
            ])
            ->add('Publish')
            ->add('country',CountryType::class)
            ->add('CoverImage',FileType::class,[
                'label' => 'Load Main image file ',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'maxWidth'=>300,
                        'maxHeight'=>300,
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])
                ],
            ])
            ->add('availability')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
