<?php

namespace App\Form;

use App\Entity\Author;

use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Doctrine\ORM\EntityManagerInterface;

class AuthorChoiceLoader implements ChoiceLoaderInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadChoiceList($value = null)
    {
        $authors = $this->entityManager->getRepository(Author::class)
            ->createQueryBuilder('a')
            ->select('a.username')
            ->getQuery()
            ->getArrayResult();

        $authorChoices = array_column($authors, 'username');

        return $authorChoices;
    }

    public function loadChoicesForValues(array $values, $value = null)
    {
        return $values;
    }

    public function loadValuesForChoices(array $choices, $value = null)
    {
        return $choices;
    }
}
