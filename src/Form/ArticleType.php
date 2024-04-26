<?php

namespace App\Form;

use App\Entity\Article;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleType extends AbstractType
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('prix')
            ->add('category', EntityType::class, [
                'class' => 'App\Entity\Category',
                'query_builder' => function (CategoryRepository $repo) {
                    return $repo->createQueryBuilder('c')
                        ->orderBy('c.titre', 'ASC');
                },
                'choice_label' => 'titre',
                'label' => 'Catégorie'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
