<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends BaseFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $yearChoices = [];
        for ($year = 1970; $year <= date('Y'); $year++) {
            $yearChoices[$year] = $year;
        }

        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true,
            ]);

        if (!$options['embedded']) {
            $builder->add('compositionYear', ChoiceType::class, [
                'label' => 'Composition year',
                'choices' => $yearChoices
            ])
                ->add('artists', CollectionType::class, [
                    'label' => 'Composition year',
                    'entry_type' => ArtistType::class,
                ])
                ->add('albums', CollectionType::class, [
                    'label' => 'Composition year',
                    'entry_type' => AlbumType::class,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}