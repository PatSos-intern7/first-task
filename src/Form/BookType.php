<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\BookGenre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
