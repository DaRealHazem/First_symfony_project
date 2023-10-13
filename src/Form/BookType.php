<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Book; // Import the Book entity
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\AuthorType;
use App\Entity\Author;

class BookType extends AbstractType
{
    private $entityManager;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography',
                ],
            ])
            ->add('reference', TextType::class, [
                'mapped' => false,
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username',
                'multiple' => false,
                
            ])
            ->add('publicationDate')
            ->add('published');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'entity_manager' => null, // Custom option to pass the EntityManager
        ]);
    }
}
